<?php

namespace App\Console\Commands;

use App\Base\Constants\Masters\DriverDocumentStatus;
use App\Mail\Driver\DriverDocumentExpiryMail;
use App\Models\Admin\DriverDocument;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyDriverDocumentExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:document:expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mail to Driver regards Document Expires';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $driverDocuments = DriverDocument::where('expiry_date', '>', Carbon::today()->toDateString())
                                            // ->where('document_status',DriverDocumentStatus::UPLOADED_AND_APPROVED)
                                            ->get();

        foreach ($driverDocuments as $doc) {
            $docExpiryDate = $doc->getOriginal('expiry_date');
            // $docExpiryDate = Carbon::parse($doc->getOriginal('expiry_date'))->toDateString();

            $today = Carbon::today()->toDateString();
            $ExpiryDate = Carbon::parse($docExpiryDate)->subDays(5)->toDateString();

            $driverArray = array();
            $driverArray['driverEmail'] = $doc->email;
            $driverArray['documentName'] = $doc->document_name;
            $driverArray['driverName'] = $doc->driver->name;
            $driverArray['documentExpiry'] = Carbon::parse($docExpiryDate)->toDateString();
            $driverArray['docExpiryInDays'] = Carbon::parse($docExpiryDate)->diffInDays();
            $driverArray['driverPhone'] = $doc->driver->mobile;


            if (Carbon::parse($docExpiryDate)->subDays(1)->toDateString() == $today) {
                $isApproved = $doc->driver->approve;

                if ($isApproved == 1) {
                    $doc->driver->update([
                        'approve' => false
                    ]);
                }

                $doc->update([
                    'document_status' => DriverDocumentStatus::EXPIRED
                ]);
            }

            if ($ExpiryDate <= $today) {
                $driverEmail = $doc->driver->email;
                Mail::to($driverEmail)->send(new DriverDocumentExpiryMail($driverArray));
            }
        }

        $this->info('Email send');
    }
}
