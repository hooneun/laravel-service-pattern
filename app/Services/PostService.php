<?php


namespace App\Services;


use App\Repositories\PostRepository;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function getAll()
    {
        return $this->postRepository->getAll();
    }

    public function getById($id)
    {
        return $this->postRepository->getById($id);
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'title' => 'bail|min:2',
            'description' => 'bail|max:255',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $post = $this->postRepository->update($data, $id);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
        }
        DB::commit();

        return $post;
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try  {
            $post = $this->postRepository->delete($id);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());

            throw new \InvalidArgumentException('Unable to delete post data');
        }
        DB::commit();

        return $post;
    }

    /**
     * Validate post data
     * Store !
     *
     * @param array $data
     * @return String
     */
    public function save($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->postRepository->save($data);
    }
}
