<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB,
    Auth;

class UserCategory extends Model
{
    use SoftDeletes;

    protected $table = 'category_user';
    protected $fillable = ['user_id', 'category_id'];
    
    /**
     * Insert Multiple categry for user
     * Author: Jinandra
     * Date: 05-01-2017
     *
     * @param  int $user_id
     * @param  int $category_id
     * @return array
     */
    public static function insertUserCategory(Array $data)
    {        
        $postdataarr = array(
                            'user_id' => $data['user_id'],
                            'category_id' => $data['category_id'],
                            );
                
        $insrtRes = UserCategory::Create($postdataarr);        
        
        if( $insrtRes )
        {
            return $insrtRes;
        }
        else{
            return false;
        }
    }  
    
    /**
     * Delete all category of user
     * Author: Jinandra
     * Date: 05-01-2017
     *
     * @param  int $user_id
     * @return array
     */
    public static function deleteCategoryData($user_id)
    {   
        $deleteRes = DB::table((new UserCategory)->getTable())
                     ->where('user_id', '=', $user_id)
                     ->delete();
        
        if( $deleteRes )
        {
            return $deleteRes;
        }
        else{
            if( $deleteRes == "" ){
                $deleteRes = 1; 
            }
            return $deleteRes;
        }
    }
    
    /**
     * Get User's category
     * Author: Jinandra
     * Date: 05-01-2017
     *
     * @param  int $user_id
     * @return array
     */
    public static function userCategoryData($user_id)
    {   
        $usercategoryList = DB::table((new UserCategory)->getTable())
                     ->select(DB::raw('GROUP_CONCAT(DISTINCT category_id) as user_category'))
                     ->where('user_id', '=', $user_id)
                     ->groupBy('user_id')
                     ->first();
                        
        if( $usercategoryList )
        {
            return $usercategoryList;
        }
        else{
            return false;
        }
    }
}
