<?php


namespace App\Repositories;


use App\Models\Post;

class PostRepository
{
    /**
     * @var Post
     */
    protected $post;

    /**
     * PostRepository constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getById($id)
    {
        return $this->post->find($id);
    }

    public function getAll()
    {
        return $this->post->get();
    }

    public function save($data)
    {
        $post = new $this->post;

        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->save();

        return $post->fresh();
    }
}
