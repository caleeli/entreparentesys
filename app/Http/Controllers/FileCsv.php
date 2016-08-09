<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class FileCsv extends Controller
{
    //
    public function store(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required',
        ]);
        $image = $request->file('csv');
        $fileNameFull = 'csv/' . $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
        Storage::disk('local')->put($fileNameFull, File::get($image));
        if (Storage::exists($fileNameFull)) {
            $contents = Storage::disk('local')->url($fileNameFull);
            $csv = Reader::createFromPath($contents);
            //get the first row, usually the CSV header
            $headers = $csv->fetchOne();
            //get 25 rows starting from the 11th row
            $res = $csv->setOffset(10)->setLimit(25)->fetchAll();
        }
        return back()->with('success', 'Image Upload successful');
    }

    public function show()
    {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=file.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $reviews = Reviews::getReviewExport($this->hw->healthwatchID)->get();
        $columns = array('ReviewID', 'Provider', 'Title', 'Review', 'Location', 'Created', 'Anonymous', 'Escalate', 'Rating', 'Name');

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($reviews as $review) {
            fputcsv($file, array($review->reviewID, $review->provider, $review->title, $review->review, $review->location, $review->review_created, $review->anon, $review->escalate, $review->rating, $review->name));
        }
        exit();
    }
}
