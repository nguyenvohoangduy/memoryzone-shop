<?php
/**
 * Created by PhpStorm.
 * User: hoangduy
 * Date: 12/9/17
 * Time: 10:41 AM
 */

namespace App\Services\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Repositories\MailboxRepository;

class MailboxService
{
    private $mailboxRepository;
    private $infoBasic;

    public function __construct(MailboxRepository $mailboxRepository) {
        $this->mailboxRepository = $mailboxRepository;
        $this->infoBasic                 = $this->mailboxRepository->getInfoBasic();
    }

    public function index() {
        $data = $this->mailboxRepository->getAllMails();

        return [
            'data'      => $data,
            'infoBasic' => $this->infoBasic,
        ];
    }

    public function show($id) {

        $mailbox = $this->mailboxRepository->find($id);

        if (0 == $mailbox->status) {
            $this->updateStatus($id);
        }

        $data = $this->mailboxRepository->getMail($id);

        return [
            'data'      => $data,
            'infoBasic' => $this->infoBasic,
        ];
    }


    public function showTrash() {
        $data = $this->mailboxRepository->getAllTrashes();

        return [
            'data'      => $data,
            'infoBasic' => $this->infoBasic,
        ];
    }

    public function moveTrash($request) {
        $arrayId = $this->getIdDelete($request);

        return $this->updateTrash($arrayId);
    }

    public function deleteTrash($request) {
        $arrayId = $this->getIdDelete($request);

        return $this->deletePerTrash($arrayId);
    }


    public function deletePerTrash($arrayId) {
        foreach ($arrayId as $item) {
            $result = $this->mailboxRepository->destroy($item);

            if (!$result) {
                return "false";
            }
        }

        return "true";
    }

    public function updateTrash($arrayId) {
        $data = [
            'trash' => 1,
        ];
        foreach ($arrayId as $item) {
            $mailbox = $this->mailboxRepository->find($item);
            if (0 == $mailbox->trash) {
                $result = $this->mailboxRepository->update($data, $item);
                if (!$result) {
                    return "false";
                }
            }
        }

        return "true";
    }


    public function updateStatus($id) {

        $data = [
            'status' => 1,
        ];

        $this->mailboxRepository->update($data, $id);
    }

    public function destroy($id) {
        $this->mailboxRepository->destroy($id);

        return redirect()
            ->route($this->infoBasic['route'] . '.index')
            ->with(['noticeMassage' => 'Deleted!, Your data has been deleted., success']);
    }

    public function getIdDelete($request) {
        if ($request->ajax()) {
            if ($request->has('id')) {
                return $request->id;
            }
            else {
                Log::error("MailboxService getIdDelete can't get mail ajax");

                return "No mail checked";
            }
        }
        else {
            Log::error("MailboxService getIdDelete No ajax");

            return "No ajax";
        }


    }

//    public function sendMail($id, Mailable $mailable) {
//        if($id != -1){
//            $mailbox = $this->mailboxRepository->find($id);
//            Mail::to($mailbox->email)
//                ->send($mailable);
//        }
//        else{
//            Mail::to(env("MAIL_FROM_ADDRESS"))
//                ->send($mailable);
//        }
//    }
}