<?php

namespace App\Models;

use App\Models\BaseModel;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogViewer extends BaseModel
{
    protected $table = 'blog_viewers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['blog_id','ip_address','hit'];

    /**
    * Get project
    */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function hitCount($id){


        $periodType = request()->get('period_type', 'date');
        $periodFrom = request()->get('period_from', Carbon::now()->subDays(6)->format('d/m/Y'));
        $periodTo = request()->get('period_to', Carbon::now()->format('d/m/Y'));
        $parsedFrom = '';
        $parsedTo = '';
        // var_dump('tes').die();
        $data = collect([]);
		switch ($periodType) {
            case 'date':
            $parsedFrom = Carbon::createFromFormat('d/m/Y', $periodFrom);
                $parsedTo = Carbon::createFromFormat('d/m/Y', $periodTo);
                        $data = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
							->map(function($item) use($id){
								$result = BlogViewer::where('blog_id',$id)->whereDate('created_at','=',$item->format('Y-m-d'))->sum('hit') ? : 0;
								return intval($result);
							})
							->values();
            break;
            case 'month':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/$periodFrom");
                $parsedTo = Carbon::createFromFormat('d/m/Y', "01/$periodTo");

                $data = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
                            ->filter(function($item) {
                                return $item->day === 1;
                            })
                            ->map(function($item) use($id){
                                $result = BlogViewer::where('blog_id',$id)->whereMonth('created_at','=',$item->month)->whereYear('created_at','=',$item->year)->sum('hit') ? : 0;
                                return intval($result);
                            })
                            ->values();
                break;
            case 'year':
                    $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/01/$periodFrom");
                    $parsedTo = Carbon::createFromFormat('d/m/Y', "01/01/$periodTo");
                    $diff = $parsedTo->diffInYears($parsedFrom);
    
                    $data = collect(range(0, $diff))
                                ->map(function($number) use ($parsedFrom) {
                                    return $parsedFrom->year + $number;
                                })
                                ->map(function($year) use($id){
                                    $result = BlogViewer::where('blog_id',$id)->whereYear('created_at', '=', $year)->sum('hit') ?: 0;

                                    return intval($result);
                                })
                                ->values();
                    break;
                
                default:
                    break;
        }
        return $data;
    }
}
