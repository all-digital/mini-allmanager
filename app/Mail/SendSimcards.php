<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SendSimcards extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $callerid;
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $login = Auth::user()->login;


        if($this->request->acao == 'renovar'){
            $subject = 'Renovação de simcards';
        }else{
            $subject = 'Cancelamento de simcard';
        }         

               
        return $this->from('example@example.com')
               ->to('allcom@telecom')
               ->subject($subject)
               ->view('mail.simcards.cancel_renew',['login'=>$login, 'request'=> $this->request]);

             //to é para quem vai o email
            //    ->replyTo(); // a resposta vai para o email que esta preenchido ai

    }//end methods

}//end class        
