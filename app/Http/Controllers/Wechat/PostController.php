<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午6:16
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\PostLogic\PostLogic;

class PostController
{
    /**
     * 发表贴子
     *
     * @author yezi
     *
     * @return array
     * @throws ApiException
     */
    public function store()
    {
        $user = request()->input('user');
        $content = request()->input('content');
        $imageUrls = request()->input('attachments');
        $location = request()->input('location');
        $private = request()->input('private');

        if(empty($content)){
            throw new ApiException('内容不能为空',6000);
        }

        $result = app(PostLogic::class)->save($user,$content,$imageUrls,$location,$private);

        return collect($result)->toArray();
    }

    public function postList()
    {
        $user = request()->input('user');

        $posts = app(PostLogic::class)->getPostList($user);

        $posts = collect($posts)->map(function ($post){

            $poster = $post['poster'];
            $post = collect($post)->forget('poster');
            $post['poster']  = [
                'id'=>$poster->id,
                'nickname'=>$poster->nickname,
                'avatar'=>$poster->avatar,
                'college_id'=>$poster->college_id,
                'created_at'=>$poster->created_at,
            ];

            return $post;
        });

        return collect($posts)->toArray();
    }

}