<?php


namespace App\Services;


use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Validator;

class PostService
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * PostService constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function save($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->postRepository->save(data);
    }
}
