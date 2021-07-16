<?php

namespace App\Jobs;

use App\Imports\ProductImport;
use App\Models\Merk;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;
    protected $filename;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($category, $filename)
    {
        $this->category = $category;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // import data excel yang ada di storage uploads
        $files = (new ProductImport)->toArray(storage_path('app/public/uploads/' . $this->filename));

        // loop the data excel
        foreach ($files[0] as $row) {

            $explodeURL = explode('/', $row[5]);
            $explodeExtention = explode('.', end($explodeURL));
            $filename = time() . Str::random(6) . '.' . end($explodeExtention);

            // download gambar
            file_put_contents(storage_path('app/public/products/') . $filename, file_get_contents($row[5]));
            $merk = Merk::where("name", "like", "%$row[3]%")->first();
            $data = [
                'name' => $row[0],
                'slug' => Str::slug($row[0]),
                'price' => $row[2],
                'merk_id' => $merk->id,
                'category_id' => $this->category,
                'image' => 'products/' . $filename,
                'weight' => $row[6],
                'desc' => [$row[7], $row[8], $row[9]],
                'fulldesc' => $row[1],
            ];
            // simpan data database
            Product::create($data);
        }
        Storage::delete('uploads/' . $this->filename);
    }
}
