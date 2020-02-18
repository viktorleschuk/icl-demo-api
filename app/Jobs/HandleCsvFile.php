<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;


class HandleCsvFile
{
    use Dispatchable;

    protected $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Csv\Exception
     */
    public function handle()
    {
        $reader = Reader::createFromString($this->file->get());

        $reader->setHeaderOffset(0);
        $results = $reader->getRecords();

        $items = [];
        foreach ($results as $offset => $record) {

            $items[] = $record;
        }

        return Arr::get($this->validateArray($items), 'items', []);
    }

    /**
     * @param $items
     * @return array
     */
    private function validateArray($items)
    {
        $validator = Validator::make([
            'items' => $items
        ], [
            'items.*.name' => 'required|max:255',
            'items.*.quantity' => 'required|integer',
            'items.*.price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('CSV file invalid format. CSV file should have name, price, quantity headers', 422);
        }

        return $validator->validated();
    }
}
