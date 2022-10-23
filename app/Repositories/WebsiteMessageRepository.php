<?php

namespace App\Repositories;

use App\Models\WebsiteMessage;

class WebsiteMessageRepository extends BaseRepository
{
    /**
     * WebsiteMessageRepository constructor.
     * @param WebsiteMessage $websiteMessage
     */
    public function __construct(WebsiteMessage $websiteMessage)
    {
        $this->model = $websiteMessage;
    }

    /**
     * @param array $data
     * @return ?WebsiteMessage
     */
    public function findWebsiteMessage(array $data): ?WebsiteMessage
    {
        return $this->model->where("user_website_id", $data['user_website_id'])->first();
    }

    /**
     * @param array $data
     * @return ?WebsiteMessage
     */
    public function createWebsiteMessage(array $data): ?WebsiteMessage
    {
        return $this->updateOrCreate(null, $data);
    }

    /**
     * @param array $data
     * @param WebsiteMessage $websiteMessage
     * @return WebsiteMessage
     */
    public function updateWebsiteMessage(array $data, int $id): WebsiteMessage
    {
        return $this->updateOrCreate($id, $data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getWebsiteMessageByUserWebsiteId(int $id)
    {
        return $this->model->where('user_website_id', $id)->get();
    }
}
