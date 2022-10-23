<?php

namespace App\Repositories;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

abstract class BaseRepository
{
    /**
     * The Model instance init only one time
     *
     * @var Object
     */
	protected $rootModel;

	/**
	 * The Model instance.
	 *
	 * @var Object
	 */
    protected $model;

    /**
	 * Get number of records.
	 *
	 * @return array
	 */
	public function getNumber(): array
	{
		$total = $this->model->count();
		$new = $this->model->whereSeen(0)->count();
		return compact('total', 'new');
	}

    /**
     * @param string $type
     * @param string $column
     * @return int
     */
	public function getMaxOrMinValueByColumn(string $type, string $column): int
    {
        if (in_array($type, ['max', 'min'])) {
            return $this->model->{$type}($column) ?: 0;
        }

        return 0;
    }

    /**
     * Destroy a model.
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
	public function destroy(int $id): ?bool
	{
		$result = $this->getById($id);
		if ($result) {
			return $result->delete();
		}

		return null;
	}

	/**
	 * Destroy Many a model.
	 *
	 * @param  array $ids
	 * @return void
	 */
	public function destroyMany(array $ids)
	{
		$this->model->destroy($ids);
	}

	/**
	 * Replicate a model.
	 *
	 * @param  int $id
	 * @return void
	 */
	public function replicate(int $id)
	{
		$this->getById($id)->replicate()->save();
	}

	/**
	 * Replicate Many a model.
	 *
	 * @param  array $ids
	 * @return void
	 */
	public function replicateMany(array $ids)
	{
		$record = $this->model->find($ids);

		$record->each(function ($item) {
			$item->replicate()->save();
		});
	}

    /**
     * Update Or Create a model.
     *
     * @param int|null $id
     * @param array $data
     * @return Object
     */
	public function updateOrCreate(?int $id = null, array $data)
	{
		$object = $this->model->withoutGlobalScope(ActiveScope::class)->find($id);

		if (empty($object)) { // insert
			$className = get_class($this->model);
			$object = new $className;

			if ($this->checkHasColumn('created_id')) {
				$object->created_id = Auth::id();
			}
		}

		foreach ($data as $key => $value) {
			$object->{$key} = ($key === 'sort' && empty($object->{$key})) ? 0 : $value;
		}

		if ($this->checkHasColumn('updated_id')) {
			$object->updated_id = Auth::id();
		}

		$object->save();

		return $object;
	}

	/**
	 * Get Model by id.
	 *
	 * @param int|null $id
	 */
	public function getById(?int $id)
	{
		return $this->model->find($id);
	}

	/**
	 * Get Model by ids.
	 *
	 * @param  array  $ids
	 * @return Collection
	 */
	public function getByIds(array $ids): Collection
	{
		return $this->model->find($ids);
	}

    /**
     * @return Collection
     */
	public function getAll(): Collection
	{
		return $this->model->get();
	}

    /**
     * @return Object
     */
    public function getFirst()
    {
        return $this->model->first();
    }

	/**
	 * @return string
	 */
	public function getPrimaryKey(): string
	{
		return $this->model->getKeyName();
	}

	public function resetModel(): self
    {
        if (!empty($this->rootModel)) {
            $this->model = $this->rootModel;
        }

        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
	public function setModel(string $model): self
	{
	    if (empty($this->rootModel)) {
	        $this->rootModel = $this->model;
        }

		$this->model = $this->{$model};

		return $this;
	}

	/**
	 * @param string $column
	 * @return bool
	 */
	public function checkHasColumn(string $column): bool
	{
		return Schema::hasColumn($this->model->getTable(), $column);
	}

	/**
	 * @param string $table
	 * @return bool
	 */
	public function checkHasTable(string $table): bool
	{
		return Schema::hasTable($table);
	}

    /**
     * @return array
     */
    public function getColumnAndTypeOfTable(): array
    {
        $result = [];
        $columns = Schema::getColumnListing($this->model->getTable());
        if ($columns) {
            foreach ($columns as $column) {
                $result[] = [
                    'name' => $column,
                    'type' => Schema::getColumnType($this->model->getTable(), $column)
                ];
            }
        }

        return $result;
    }

    /**
     * @return $this
     */
	public function withoutActiveScope(): self
    {
        $this->model = $this->model->withoutGlobalScope(ActiveScope::class);
        return $this;
    }
}
