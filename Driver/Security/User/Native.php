<?php

function F_Native_Step1 ($Args)
{
    Page::Nest('Application/Gate/Input');
}

function F_Native_Step2 ($Args)
{
    $Agent = new Object('_User');
    
    if ($Agent->Query('=Login='.Server::Get('Login')))
    {
        Client::$Ticket->Set('MayBe', $Agent->Name);
        $Authorizers = $Agent->Get('Authorizer:Installed', false);

        if (!is_array($Authorizers ))
                $Output = '<l>Auth:BlindAuth</l>';
        else
        {
            $Output = '';
            foreach($Authorizers as $Authorizer)
            {
                $Elements = $Agent->Get('Authorizer:'.$Authorizer, false);
                foreach($Elements as $Element)
                    $Output.= Page::Get('Auth/Input/'.$Authorizer);
            }
        }
        Page::Add($Output);
        return true;
    }
    else
        throw new WTF ('User not found', 4040);
}

function F_Native_Step3 ($Args)
{
    if (true or Code::E('Security/CAPTCHA', 'Check', array('Ticket'=>Client::$Ticket)))
        {
            if (Client::$Agent->Load(Client::$Ticket->Get('MayBe')))
            {
                $Authorizers = Client::$Agent->Get('Authorizer:Installed', false);
                $Output = '';
                $Decisions = array();

                if (is_array($Authorizers))
                    foreach($Authorizers as $Authorizer)
                    {
                                if (
                                    Code::E('Security/Authorizers','Check',
                                        array('True'=> Client::$Agent->Get('Authorizer:'.$Authorizer,false),
                                              'Challenge'=>Server::Get($Authorizer)),$Authorizer)
                                    )
                                        $Decisions[$Authorizer] = true;
                                else
                                        $Decisions[$Authorizer] = false;
                    }
                    
                else
                    return 'Authorized';

                if (in_array(false, $Decisions))
                    return 'Failed';
                else
                    return 'Authorized';
            } else
                return 'No User'.Client::$Ticket->Get('MayBe');

    }
    else
        {
            Page::Nest('Application/Gate/CAPTCHAFailed');
            return 'CAPTCHAFailed';
    }
}