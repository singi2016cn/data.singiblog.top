<?php

namespace App\Console\Commands;

use App\Models\Airports;
use App\Models\City;
use App\Models\County;
use App\Models\MetroLines;
use App\Models\MetroStations;
use App\Models\MetroStationsExits;
use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OutputController extends Command
{
    private $output_path;
    private $file_name;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:output {type=pcc} {--J|json} {--S|sql}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'output {type} content into /output/{type}/YmdHis.json';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->file_name = date('Y').'.json';
        $type = $this->argument('type');
        $this->output_path = 'public/output'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR;

        $this->info($type.' file output start!');

        switch ($type){
            case 'pcc':
                $provinces = Province::select('id','name','code','initial')->get();
                if ($provinces){
                    foreach($provinces as &$province){
                        $cities = City::select('id','province_id','name','code')->where('province_id',$province->id)->get();
                        if (!$cities->isEmpty()){
                            $province['cities'] = $cities;
                            foreach($cities as &$city){
                                $counties = County::select('id','city_id','name','code')->where('city_id',$city->id)->get();
                                if (!$counties->isEmpty()) {
                                    $city['counties'] = $counties;
                                }
                            }
                        }
                    }
                }
                if ($this->option('json')){
                    $this->outJson($provinces);
                }
                if ($this->option('sql')){
                    $this->outSql(['province','city','county']);
                }

                break;
            case 'metro':
                $metro_lines = MetroLines::select('id','city_name','name')->get();
                if ($metro_lines){
                    foreach($metro_lines as &$metro_line){
                        $metro_stations = MetroStations::select('id','metro_lines_id','name')->where('metro_lines_id',$metro_line->id)->get();
                        if ($metro_stations){
                            $metro_line['metro_stations'] = $metro_stations;
                            foreach($metro_stations as &$metro_station){
                                $metro_station_exits = MetroStationsExits::select('id','metro_stations_id','name','has_wc','has_elevator')->where('metro_stations_id',$metro_station->id)->get();
                                if ($metro_station_exits){
                                    $metro_station['metro_station_exits'] = $metro_station_exits;
                                }
                            }
                        }
                    }
                }
                if ($this->option('json')){
                    $this->outJson($metro_lines);
                }
                if ($this->option('sql')){
                    $this->outSql(['metro_lines','metro_stations','metro_station_exits']);
                }
                break;
            case 'airport':
                $airports = Airports::select('id','city_name','name')->get();
                if ($this->option('json')){
                    $this->outJson($airports);
                }
                if ($this->option('sql')){
                    $this->outSql(['airports']);
                }
                break;
            default:

        }
    }

    private function outJson($data){
        Storage::put($this->output_path.$this->file_name, json_encode($data));
        $this->info('file output into '.$this->output_path.$this->file_name.' success');
    }

    private function outSql($tables){
        $DB_HOST = getenv('DB_HOST');
        $DB_DATABASE = getenv('DB_DATABASE');
        $DB_USERNAME = getenv('DB_USERNAME');
        $DB_PASSWORD = getenv('DB_PASSWORD');

        $dumpfname = storage_path('app/'.$this->output_path) . date('Y').".sql";
        $command = "mysqldump --add-drop-table --host=$DB_HOST --user=$DB_USERNAME ";
        if ($DB_PASSWORD) $command.= "--password=". $DB_PASSWORD ." ";
        $command.= $DB_DATABASE;
        $command.= ' '.implode(' ',$tables).' ';
        $command.= " > " . $dumpfname;
        system($command);
        $this->info('file output into '.$dumpfname.' success');
    }
}
