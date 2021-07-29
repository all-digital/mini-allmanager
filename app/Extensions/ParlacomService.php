<?php

namespace App\Extensions;


// use Illuminate\Support\Facades\Mail;

//use Illuminate\Contracts\Cache\Repository as Cache;
use SimpleXMLElement;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Cache\Repository as Cache;

// use Illuminate\Support\Facades\Cache;


class ParlacomService
{
    protected $client;
    protected $company;
    protected $admin;
    protected $adminPwd;
    protected $url;
    // private $cache;

    public function __construct(Client $client, Cache $cache)
    {
        $this->url       = 'allcom.parlacom.net/cgi-bin/parla';
        $this->company   = 'allcom';
        $this->admin     = 'allcomcrm';
        $this->adminPwd  = 'allcomcrm';
        $this->client    =  $client;
        $this->cache     = $cache;

        // $this->client    = $client;
        // $this->company   = $company;
        // $this->admin     = $admin;
        // $this->adminPwd  = $adminPwd;
        // $this->url       = $url;
    }

    private function getHeaders()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
    }

    private function replaceAccents($str)
    {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', '&');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '');

        return str_replace($a, $b, $str);
    }

    private function loadXML($xml)
    {
        return new SimpleXMLElement(utf8_encode($this->replaceAccents($xml)));
    }

    public function getSession()
    {
        $sess = $this->cache->get('session');
        if (null == $this->cache->get('session') || strpos($sess, 'yes') === false) {

            $response = $this->client->post($this->url, [
                'form_params' => [
                    'Function' => 'Login',
                    'Company'  => $this->company,
                    'Admin'    => $this->admin,
                    'AdminPwd' => $this->adminPwd,
                    'Output_type' => 'xml'
                ],
                'headers' => $this->getHeaders()
            ]);
    
            $contents = $response->getBody()->getContents();
            $xml = $this->loadXML($contents);

            $sessionid = (string) $xml->response->sessionid;
    
            //Cache::put('session', $sessionid, 30);
            $this->cache->put('session', $sessionid, 30);
        }
            // return Cache::get('session');
            return $this->cache->get('session');
    }

    public function getUser($login)
    {        
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'GetUser',
                'Company'    => $this->company,
                'Admin'      => $this->admin,
                'AdminPwd'   => $this->adminPwd,
                'Login'      => $login,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);
        
        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
        
        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        return $xml->response;
    }
    
    public function addUser(array $data, $owner = 'clientes')
    {
        $response = $this->client->post($this->url,  [
            'form_params' => [
                'Function'       => 'AddUser',
                'Company'        => $this->company,
                'Admin'          => $this->admin,
                'AdminPwd'       => $this->adminPwd,
                'Login'          => $data['login'],
                'Password'       => $data['password'],
                'ChangePassword' => ($data['change_password'] == 1) ? 'yes' : 'no',
                'FirstName'      => $data['company'],
                'LastName'       => $data['company_fantasy'],
                'Address1'       => isset($data['address']) ? $data['address'] : $data['street'],
                'Email'          => $data['email'],
                'Gtalk'          => $data['email'],
                'City'           => $data['city'],
                'State'          => $data['state'],
                'Country'        => $data['country'],
                'Zip'            => isset($data['postal']) ? $data['postal'] : $data['zip'],
                'Phone'          => $data['phone'],
                'Mobile'         => $data['mobile'],
                'Record'         => ($data['record'] == 1) ? 'yes' : 'no',
                'Due'            => $data['due'],
                'InvoiceDate'    => $data['invoice_date'],
                'AgentLogin'     => $data['seller'],
                'SalesResp'      => $data['seller'],
                'UserId'         => $data['uid'],
                'LogoUrl'        => isset($data['image']) ? $data['image'] : null,
                'Field4'         => $data['omie_reference'] ?? null,
                'SessionID'      => $this->getSession(),
                'Owner'          => $owner,
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        return $xml->response;
    }

    public function changeUser($login, $data)
    {
        $body = [
            'Function'  => 'ChangeUser',
            'Company'   => $this->company,
            'Admin'     => $this->admin,
            'Login'     => $login,
            'SessionID' => $this->getSession(),
            'Output_type' => 'xml'
        ];

        foreach ($data as $field => $value)
        {
            if (isset($data[$field]) && !empty($data[$field])) {
                $body[$field] = $value;
            }
        }

        $response = $this->client->post($this->url,  [
            'form_params' => $body,
            // 'form_params' => [
                
                // 'Function'       => 'ChangeUser',
                // 'Company'        => $this->company,
                // 'Admin'          => $this->admin,
                // 'AdminPwd'       => $this->adminPwd,
                // 'Login'          => $data['login'],
                // 'Password'       => $data['password'],
                // 'ChangePassword' => $data['change_password'],
                // 'FirstName'      => $data['company'],
                // 'LastName'       => $data['company_fantasy'],
                // 'Address1'       => $data['address'],
                // 'Email'          => $data['email'],
                // 'Gtalk'          => $data['email'],
                // 'City'           => $data['city'],
                // 'State'          => $data['state'],
                // 'Country'        => $data['country'],
                // 'Zip'            => $data['postal'],
                // 'Phone'          => $data['phone'],
                // 'Mobile'         => $data['mobile'],
                // 'Record'         => $data['record'],
                // 'Due'            => $data['due'],
                // 'InvoiceDate'    => $data['invoice_date'],
                // 'AgentLogin'     => $data['seller'],
                // 'SalesResp'      => $data['seller'],
                // 'SessionID'      => $this->getSession(),
                // 'Owner'          => $owner,
            // ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        return $xml->response;
    }

    public function getAllUsers($login)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'  => 'GetAllUsers',
                'Company'   => $this->company,
                'Login'     => $login,
                'SessionID' => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'heeaders' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        // $contents = mb_convert_encoding($contents, "UTF-8", 'auto');
        $xml      = $this->loadXML($contents);

        if ($xml->response->errorcode > 0) {

            $this->cache->forget('session');
            
            return null;
        }

        $result = $xml->response->user;

        $users = [];

        foreach ($result as $string)
        {
            // clientes|00057274000117|******|suportemicks7@hotmail.com|2019-06-10 14:43:58|1|1|MICKS TELECOM EIRELI MICKS TELECOM|pt|1|0|MICKS TELECOM EIRELI|14|00.057.274/0001-17||||"

            list($owner, $login, $password, $email, $activated_at, $due_date, $payment_type,
                $description, $language, $turn_billing, $bank_balance, $first_name, $due, $user_id,
                $field1, $field2, $field3, $field4) = explode('|', $string);

                if ($owner == 'admin') {
                    continue;
                }

                $user = [
                    'owner'        => $owner,
                    'login'        => $login,
                    'password'     => $password,
                    'email'        => $email,
                    'activated_at' => $activated_at,
                    'due_date'     => $due_date,
                    'payment_type' => $payment_type,
                    'description'  => $description,
                    'language'     => $language,
                    'turn_billing' => $turn_billing,
                    'bank_balance' => $bank_balance,
                    'first_name'   => $first_name,
                    'due'          => $due,
                    'user_id'      => $user_id,
                    'field1'       => $field1,
                    'field2'       => $field2,
                    'field3'       => $field3,
                    'omie_ref'     => $field4 ?: null,
                ];

                $users[] = $user;
        }

        return $users;
    }

    public function getOpenInvoices($login, $startDate = null, $endDate = null, $id = null)
    {
        if (is_null($startDate) || is_null($endDate)) {
            // $startDate = now()->startOfMonth()->format('Y-m-d');
            // $endDate   = now()->format('Y-m-d');
        }

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'GetOpenInvoices',
                'Company'    => $this->company,
                'Admin'      => 'Admin',
                // 'AdminPwd'   => $this->adminPwd,
                'StartDate'  => $startDate,
                'EndDate'    => $endDate,
                'Login'      => $login,
                'Id'         => $id,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml      = $this->loadXML($contents);

        if ($xml->response->errorcode > 0) {
            
            return null;
        }
    
        $result = (array) $xml->response->returns->return ?? [];
    
        $invoices = [];

        foreach ($result as $string)
        {
            list(
                $invoiceId, $login, $date, $dueDate, 
                $firstName, $lastName, $numItems, $total,
                $userId, $email, $phone, $mobile, 
                $discount, $status, $owner, $invDate, $type
            ) = explode('|', $string);

            if (strlen($date) == 10) {

                $date = now()->createFromFormat('Y-m-d', $date);
            }

            if (strlen($date) > 10) {
                $date = now()->createFromFormat('Y-m-d H:i:s', $date);
            }
        
            // $dueDay = ((int) $dueDate - now()->createFromFormat('Y-m-d',  $date)->day);
            // $invDay = ((int) $invDate - now()->createFromFormat('Y-m-d',  $date)->day);

            $invoice = [
                'invoiceId' => $invoiceId,
                'login'     => $login,
                'date'      => $date,
                'dueDate'   => $dueDate,
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'numItems'  => $numItems,
                'total'     => $total,
                'userId'    => $userId,
                'email'     => $email,
                'phone'     => $phone,
                'mobile'    => $mobile,
                'discount'  => $discount,
                'status'    => $status,
                'owner'     => $owner,
                'invDate'   => $invDate,
                'type'      => $type    
            ];

            $invoices[] = $invoice;
        }

        return $invoices;
    }

    public function getPaidInvoices($login, $start = null, $end = null)
    {
        if (is_null($start) || is_null($end)) {
            $start = now()->startOfMonth()->format('Y-m-d');
            $end   = now()->format('Y-m-d');
        }

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ShowPaidInvoices',
                'Company'    => $this->company,
                'Login'      => $login,
                'Admin'      => 'Admin',
                'StartDate'  => $start,
                'EndDate'    => $end,
                'All'        => 'yes',
                'Full'       => 'yes',
                'Paid'       => 'yes',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml      = $this->loadXML($contents);
        
        $invoices = [];
        $result   = (array) $xml->response->return;
        
        foreach ($result as $string)
        {
            list($company, $f00, $numItems, $paymentDate, $total, $login, $firstName, $lastName, $invoiceId, $date, $dueDate) = explode('|', $string);

            if (strlen($date) == 10) {
                $date = now()->createFromFormat('Y-m-d', $date);
            } else if (strlen($date) > 10) {
                $date = now()->createFromFormat('Y-m-d H:i:s', $date);
            }

            if (strlen($dueDate) == 10) {
                $dueDate = now()->createFromFormat('Y-m-d', $dueDate);
            } else if (strlen($dueDate) > 10) {
                $dueDate = now()->createFromFormat('Y-m-d H:i:s', $dueDate);
            }

            if (strlen($paymentDate) == 10) {
                $paymentDate = now()->createFromFormat('Y-m-d', $paymentDate)->format('d/m/Y');                
            } else if (strlen($paymentDate) > 10) {
                $paymentDate = now()->createFromFormat('Y-m-d H:i:s', $paymentDate)->format('d/m/Y H:i:s');
            }
        
            $invoices[] = [
                'invoiceId' => $invoiceId,
                'login'     => $login,
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'numItems'  => $numItems,
                'total'     => $total,
                'date'      => $date,
                'dueDate'   => $dueDate,
                'paymentDate' => $paymentDate
            ];
        }

        return $invoices;
    }

    public function getAllInvoices($login, $start = null, $end = null)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ApiShowInvoices',
                'Company'    => $this->company,
                'Login'      => $login,
                'Admin'      => 'Admin',
                'StartDate'  => $start,
                'EndDate'    => $end,
                'Full'       => 'yes',
                'Paid'       => 'yes',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml      = $this->loadXML($contents);
        
        $results = [];
        $result   = (array) $xml->response->invoice;
        
        foreach ($result as $string)
        {
            // 243305|unisat|2019-05-01|14|Unisat Tec. em Rast. e Monit. Ltda-ME|UNISAT TECNOLOGIA EM RASTREAMENTO|47|297.1|03.003.101/0001-04|naldo.rocha@unisat.net.br|+55 11 ▶"

            list(
                $invoiceId, $login, $date, $due, $firstName, $lastName, $numOfItens, $total, 
                $cpfCnpj, $email, $phone
            ) = explode('|', $string);

            if (strlen($date) == 10) {
                $date = now()->createFromFormat('Y-m-d', $date)->format('d/m/Y');                
            } else if (strlen($date) > 10) {
                $date = now()->createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y H:i:s');
            }

            $results[] = [
                'invoiceId' => $invoiceId,
                'login'     => $login,
                'firstName' => $firstName,
                'total'     => $total,
                'date'      => $date,
                'dueDate'   => $due
            ];
            
        }
        
        return $results;
    }

    public function getInvoiceSummary($login, $invoiceId)
    {

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'showinvoicesummary',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'LoginDest'   => $login,
                'Id'          => $invoiceId,
                'ServiceType' => 20,
                'Billitem'    => 'yes',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        $response = (array) $xml->response->return;

        $results = [];
        foreach ($response as $item)
        {
            $idx = trim(substr($item, 0, 1));

            if ($idx == '|') {
                continue;
            }
            
            list($carrier, $qtd, $subtotal, $serviceId, $billingType, $prorate) = explode('|', $item);

            $results[] = [
                'operadora' => $carrier,
                'carrier'   => $this->getCarrier($carrier),
                'quantity'  => $qtd,
                'subtotal'  => $subtotal,
                'serviceId' => $serviceId,
                'billingType' => $billingType,
                'prorate'   => $prorate
            ];
        }

        return collect($results);
    }

    public function getInvoiceItems($id, $login)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'APIShowInvItems',
                'Company'    => $this->company,
                'Admin'      => $login,
                'Id'         => $id,
                'ServiceType' => '0',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        $response = (array) $xml->response->invitem;

        $result = [];
        foreach ($response as $item)
        {
            list($id, $qtd, $unitPrice, $v0, $totalPrice, $description) = explode('|', $item);
            
            $billing = '';

            if ((strpos($description, "'Install") !== false)) {
                $billing = 'Instalação';
            }

            if ((strpos($description, "Cancellation") !== false)) {
                $billing = 'Cancelamento';
            }

            if ((strpos($description, "'M2M") !== false)) {
                $billing = 'Mensalidade';
            }

            $result[] = [
                'id' => $id,
                'qtd' => $qtd,
                'unitPrice' => $unitPrice,
                'totalPrice' => $totalPrice,
                'description' => $description,
                'cobranca' => $billing
            ];
        }

        return $result;
    }

    public function newInvoice($login, $monthYear, $runall = 'yes')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'NewInvoice',
                'company'    => $this->company,
                'admin'      => 'admin',
                'login'      => $login,
                'logindest'  => $login,
                'ap'         => 1,
                'monthyear' => $monthYear,
                'runall'    => $runall,
                'prorate'   => 'no',
                'hierarchy' => 'no',
                'noextra'   => 'yes',
                'rollup'    => 'yes',
                'local'     => 'yes',
                'detail'    => '0',
                'email'     => '',
                'output_type'=>'html',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
        
        return true;
    }

    public function newInvoiceItem($login, $id)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'NewInvoiceItem',
                'Company'     => $this->company,
                'Admin'       => 'Admin',
                'Login'       => $login,
                'Id'          => $id,
                'ServiceType' => 20,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        return true;
    }

    public function editInvoiceItem($login, array $data)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'EditInvoiceItem',
                'company'     => $this->company,
                'admin'       => 'admin',
                'login'       => $login,
                'qtd'         => $data['quantity'],
                'unitprice'   => $data['unitprice'],
                'tax'         => 0,
                'grosstotal'  => $data['total'],
                'total'       => $data['total'],
                'description' => $data['description'],
                'pincarriertmp' => $data['carrier'],
                'ServiceType' => 20,
                'id'         => $data['itemId'],
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
    
        return true;
    }

    public function makePayment(array $data)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'MakePayment',
                'Company'    => $this->company,
                // 'Login'      => $this->admin,
                'Admin'      => $this->admin,
                'AdminPwd'   => $this->adminPwd,
                'StartDate'  => $data['startdate'] ?? null,
                'EndDate'    => $endDate ?? null,
                'Id'         => $data['id'],
                'Total'      => $data['total'],
                'LoginDest'  => $data['loginDest'],
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if($xml->response->errorcode == 153) {
            return null;
        }

        return $xml->response;
    }

    public function delInvoice($login, $id)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'DelInvoice',
                'Company'    => $this->company,
                'Admin'      => 'admin',
                'Login'      => $login,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 0) {
            return true;
        }

        return false;
    }
    
    public function delInvoiceItem($login, $id)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'DelInvoiceItem',
                'Company'    => $this->company,
                'Admin'      => 'admin',
                'Login'      => $login,
                'LoginDest'  => null,
                'Id'         => $id,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 0) {
            return true;
        }

        return false;
    }

    public function addUserService($login, $description, $contractdate, $tax = 0)
    {
        $params = [
            'Function'    => 'AddGeneralService',
            'Company'     => $this->company,
            'Login'       => $login,
            'adminnobank' => 'yes',
            'PinCarrier'  => 1,
            'prefix'      => 6, //SMS
            'InstFee'     => $tax,
            'Mrc'         => 0,
            'Description' => $description,
            'ContractDate'=> $contractdate,
            'General'     => '',
            'Ap'          => 1,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = $xml->response;

        return $result;
    }

    public function changeGeneralService($serviceId, $login, $description, $contractdate, $tax = 0)
    {
        $params = [
            'Function'    => 'ChangeGeneralService',
            'Company'     => $this->company,
            'Login'       => $login,
            'adminnobank' => 'yes',
            'PinCarrier'  => 1,
            'prefix'      => 6, //SMS
            'Mrc'         => 0,
            'Description' => $description,
            'ContractDate'=> $contractdate,
            'General'     => $serviceId,
            'Ap'          => 1,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $params = [
            'Function' => 'EditInstFee',
            'Company'     => $this->company,
            'Admin'       => 'admin',
            'Login'       => $login,
            'adminnobank' => 'yes',
            'PinCarrier'  => 1,
            'prefix'      => 6, //SMS
            'InstFee'     => $tax,
            'servicetype' => 50,
            'serviceId'   => $serviceId,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = $xml->response;

        return $result;
    }

    public function getUserSMSServices($login, $carrier = 1)
    {
        $params = [
            'Function'    => 'GetAllUserServices',
            'Company'     => $this->company,
            'admin'       => 'admin',
            'Login'       => $login,
            'PinCarrier'  => $carrier,
            'ServiceType' => 50,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $services = [];
        $results = (array) $xml->response->service ?: [];

        if (!empty($results)) {

            foreach ($results as $row)
            {
                $row = explode("|", $row);

                list(
                    $login, 
                    $service, 
                    $serviceId, 
                    $installation, 
                    $monthlyPayment, 
                    $activatedAt, 
                    $field00,
                    $field01,
                    $status,
                    $field02,
                    $description,
                    $accountType,
                    $contractedAt
                ) = $row;

                $services[] = [
                    'login'        => $login,
                    'service'      => $service,
                    'serviceId'    => $serviceId,
                    'installation' => $installation,
                    'monthly'      => $monthlyPayment,
                    'activatedAt'  => now()->createFromFormat('Y-m-d H:i:s', $activatedAt),
                    'contractedAt' => $contractedAt,
                    'description'  => $description,
                    'type'         => $accountType,
                    'status'       => $status == 1 ? 'Ativo' : 'Inativo'
                ];
            }
        }

        return collect($services);
    }

    public function delUserSMSService($serviceId, $login)
    {
        $params = [
            'Function'    => 'DelService',
            'Company'     => $this->company,
            'Login'       => $login,
            'ServiceId'   => $serviceId,
            'PinCarrier'  => 1,
            'ServiceType' => 50,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $services = [];
        $results = (array) $xml->response->service ?: [];

    }

    public function simcards($login, $carrier, $search = null, $field = null, $init = 1, $perPage = 15, $orderBy = 'asc', $orderByField = 'callerid')
    {
        $params = [
            'Function'    => 'GetAllUserServices',
            'Company'     => $this->company,
            'admin'       => 'admin',
            'Login'       => $login,
            'PinCarrier'  => $carrier,
            'ServiceType' => 20,
            'total'       => $perPage,
            'ini'         => $init,
            'Orb'         => $orderBy,
            'Orbf'        => $orderByField,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        if (!is_null($search) && !is_null($field)) {
            if ($field == 'searchcid') $params['searchcid'] = $search;
            if ($field == 'searchiccid') $params['searchiccid'] = $search;
            if ($field == 'searchdesc') $params['searchdesc'] = $search;
            if ($field == 'searchmrc') $params['searchmrc'] = $search;
            if ($field == 'searchcredit') $params['searchcredit'] = $search;
            if ($field == 'searchlastdate') $params['searchlastdate'] = $search;
            if ($field == 'percent') $params['percent'] = $search;
            if ($field == 'online') $params['online'] = $search;
            
            // if ($field == 'searchinstdate') $params['searchinstdate'] = $now;
        }

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        $simcards = [];
        $result = (array) $xml->response->service;

        foreach ($result as $string)
        {            
            list($login, $serviceType, $callerid, $serviceId, $mrcValue, $createdAt, 
                $v0, $v1, $v2, $carrier, $description, $mrc, $balance, $credit, $status, $v00, 
                $consumption, $v4, $lastConn, $online, $v7, $iccid, $ip, $imsi,
                $v8, $latitude, $imei, $v9, $stopTime, 
                $v10, $v11, $v12, $v13, $v14, $individualShared, $v16, $longitude, $n0, $n1, $n2, $mcc, $mnc
            ) = explode('|', $string);
            
            if ($status == '1024') {
                $status = 'Ativo';
            } else {
                $status = 'Bloqueado';
            }
                
            if (strlen($createdAt) == 10) {
                $createdAt = now()->createFromFormat('Y-m-d', $createdAt)->format('d/m/Y');
            } else if (strlen($createdAt) > 10) {
                $createdAt = now()->createFromFormat('Y-m-d H:i:s', $createdAt)->format('d/m/Y');
            }

            if (strlen($lastConn) == 10) {
                $lastConn = now()->createFromFormat('Y-m-d', $lastConn)->format('d/m/Y');
            } else if (strlen($lastConn) > 10) {
                $lastConn = now()->createFromFormat('Y-m-d H:i:s', $lastConn)->format('d/m/Y H:i:s');
            }

            $mrc = (float) $mrc;
            $consumption = 0;

            if ($mrc > 0) {
                $balance     = str_replace(',', '', $balance);
                $consumption = ((100 * ( $mrc - $balance)) / $mrc);
            }
            
            $consumption = number_format($consumption, 2);

            $simcards[] = [
                'login'          => $login,
                'carrier'        => $carrier,
                'operadora'      => $this->getOperator($carrier),
                'imei'           => $imei,
                'description'    => $description ?? '',
                'ip'             => $ip,
                'callerid'       => $callerid,
                'iccid'          => $iccid,
                'latitude'       => $lat ?? 0,
                'longitude'      => $lng ?? 0,
                'mrc'            => $mrc . ' MB',
                'monthlyPayment' => $mrcValue . ' MB' ?? 0,
                'credit'         => $credit ?? 0,
                'currency'       => (string) $xml->response->currency,
                'balance'        => $balance,
                'consumption'    => $consumption ?? 0,
                'createdAt'      => $createdAt,
                'lastConn'       => $lastConn,
                'online'         => $online ?? 0,
                'mcc'            => $this->getMcc($mcc),
                'mnc'            => $this->getMnc($mnc),
                'type'           => $individualShared,
                'status'         => $status,
            ];
        }

        return collect($simcards);
    }

    public function getAllUserServices($login, $carrier, $search, $searchiccid = null, $searchdesc = null, $total = 15, $ini = 1, $now = null, $lastDate = null, $consumption = null, $searchmrc = null, $searchcredit = null, $orb = 'asc', $orbf = 'simcard')
    {
        $params = [
            'Function'    => 'GetAllUserServices',
            'Company'     => $this->company,
            'admin'       => 'admin',
            'Login'       => $login,
            'PinCarrier'  => $carrier,
            'ServiceType' => 20,
            'total'       => $total,
            'ini'         => $ini,
            'Orb'         => $orb,
            'Orbf'        => $orbf,
            'SessionID'   => $this->getSession(),
            'Output_type' => 'xml'
        ];

        if (!is_null($search)) {
            $params['searchcid'] = $search;
        }

        if (!is_null($searchiccid)) {
            $params['searchiccid'] = $searchiccid;
        }

        if (!is_null($searchdesc)) {
            $params['searchdesc'] = $searchdesc;
        }

        if (!is_null($now)) {
            $params['searchinstdate'] = $now;
        }

        //last connection
        if (!is_null($lastDate)) {
            $params['searchlastdate'] = $lastDate;
        }

        //consumption
        if (!is_null($consumption)) {
            $params['percent'] = $consumption;
        }
        
        //searchmrc
        if (!is_null($searchmrc)) {
            $params['searchmrc'] = $searchmrc;
        }

        //searchcredit
        if (!is_null($searchcredit)) {
            $params['searchcredit'] = $searchcredit;
        }

        $response = $this->client->post($this->url, [
            'form_params' => $params,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $lines = [];
        $result = (array) $xml->response->service;

        foreach ($result as $string)
        {            
            list($login, $serviceType, $callerid, $serviceId, $mrcValue, $createdAt, 
                $v0, $v1, $v2, $carrier, $description, $mrc, $balance, $credit, $status, $v00, 
                $consumption, $v4, $lastConn, $online, $v7, $iccid, $ip, $imsi,
                $v8, $latitude, $imei, $v9, $stopTime, 
                $v10, $v11, $v12, $v13, $v14, $individualShared, $v16, $longitude, $n0, $n1, $n2, $mcc, $mnc
            ) = explode('|', $string);
            
            if ($status == '1024') {
                $status = 'Ativo';
            } else {
                $status = 'Bloqueado';
            }
                
            if (strlen($createdAt) == 10) {
                $createdAt = now()->createFromFormat('Y-m-d', $createdAt)->format('d/m/Y');
            } else if (strlen($createdAt) > 10) {
                $createdAt = now()->createFromFormat('Y-m-d H:i:s', $createdAt)->format('d/m/Y');
            }

            if (strlen($lastConn) == 10) {
                $lastConn = now()->createFromFormat('Y-m-d', $lastConn)->format('d/m/Y');
            } else if (strlen($lastConn) > 10) {
                $lastConn = now()->createFromFormat('Y-m-d H:i:s', $lastConn)->format('d/m/Y H:i:s');
            }

            $mrc = (float) $mrc;
            $consumption = 0;

            if ($mrc > 0) {
                $balance     = str_replace(',', '', $balance);
                $consumption = ((100 * ($mrc-$balance)) / $mrc);
            }
            $consumption = number_format($consumption,2);

            // dd($string);
            // O saudlo vai sempre o valor do saldo. Se estiver -10, voce recebe -10.

            // Limite de excedente e' como se fosse saldo extra. 

            // Plano mensal = 20 megas
            // Excedentes/limite de credito = 10 megas

            // Saldo do chip agora = -7

            // consumo foi de 20 (plano totalmente usado) + 7 megas (do saldo negativo. Consumo total ate agora: 27 megas
            // Disponivel total universal = 20 do plano + 10 maximo do excedente = 30 megas

            // percentual de consumo 27/30
            // dd($mrc,$mrcValue, $credit,$balance, $consumption);

            $lines[] = [
                'login'          => $login,
                'carrier'        => $carrier,
                'operadora'      => $this->getOperator($carrier),
                'imei'           => $imei,
                'description'    => $description ?? '',
                'ip'             => $ip,
                'callerid'       => $callerid,
                'iccid'          => $iccid,
                'latitude'       => $lat ?? 0,
                'longitude'      => $lng ?? 0,
                'mrc'            => $mrc . ' MB',
                'monthlyPayment' => $monthlyPayment ?? 0,
                'credit'         => $xml->response->currency . ' ' . $mrcValue ?? $credit ?? 0,
                'balance'        => $balance,
                'consumption'    => $consumption ?? 0,
                'createdAt'      => $createdAt,
                'lastConn'       => $lastConn,
                'online'         => $online ?? 0,
                'mcc'            => $this->getMcc($mcc),
                'mnc'            => $this->getMnc($mnc),
                'type'           => $individualShared,
                'status'         => $status,
            ];
        }

        return collect($lines);
    }

    public function getAllLines($login, $carrier)
    {
        $reset = null;
        $usage = null;

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ApiGeneralPin',
                'Company'    => $this->company,
                'Login'      => $login,
                'Carrier'    => $carrier,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if($xml->response->errorcode == 153) {
            return null;
        }

        $lines = [];
        $result = (array) $xml->response->return;

        foreach ($result as $string)
        {
            list($login, $rCarrier, $callerId, $ip, $iccid, $monthlyPayment, $mrc, $online, 
                $createdAt, $startTime, $balance, $creditLimit, $consumption, $active, $localImei, $lat, $lng,$eventTime, $deviceImei, $stopTime, $lat2, $lng2) = explode('|', $string);

                // dd($geo, $eventTime);

            // list($login, $carrier, $callerid, $ip, $iccid, $monthlyPayment, $mrc, $online, 
            //     $createdAt, $startTime, $balance, $creditLimit, $consumption, $status, $imei, $lat, $lng, $eventTime, $deviceImei , $stopTime) = explode('|', $data);

            $operadora = $this->getOperator($rCarrier);
            
            if ($active == '1024') {
                $active = 'Ativo';
            } else {
                $active = 'Bloqueado';
            }

            if (strlen($createdAt) == 10) {
                $createdAt = now()->createFromFormat('Y-m-d', $createdAt)->format('d/m/Y');
            } else if (strlen($createdAt) > 10) {
                $createdAt = now()->createFromFormat('Y-m-d H:i:s', $createdAt)->format('d/m/Y');
            }
            
            if (strpos($createdAt, '-') !== false) {
                $createdAt = now()->format('d/m/Y');
            } 
            
            if (strlen($stopTime) == 10) {
                $stopTime = now()->createFromFormat('Y-m-d', $stopTime)->format('d/m/Y');
            } else if (strlen($stopTime) > 10) {
                $stopTime = now()->createFromFormat('Y-m-d H:i:s', $stopTime)->format('d/m/Y H:i:s');
            }
            
            if (strlen($eventTime) == 10) {
                $eventTime = now()->createFromFormat('Y-m-d', $eventTime)->format('d/m/Y');
            } else if (strlen($eventTime) > 10) {
                $eventTime = now()->createFromFormat('Y-m-d H:i:s', $eventTime)->format('d/m/Y H:i:s');
            } 

            if ($online == "1")  {
                $online  = 'Online';
            } else {
                $online = 'Offline';
            }

            $balance = (float) number_format($balance, 2);
            
            $mrc = (float) $mrc;
            $consumption = 0;

            if ($mrc > 0) {

                $consumption = ((100 * ($mrc-$balance)) / $mrc);
            }

            $mrc = (!is_null($mrc) && !empty($mrc)) ? $mrc . ' MB' : null;

            $lines[] = [
                'operadora'   => $operadora,
                'login'       => $login,
                'carrier'     => $rCarrier,
                'callerid'    => $callerId,
                'ip'          => $ip,
                'iccid'       => $iccid,
                'mrc'         => $mrc,
                'reset'       => $reset,
                'online'      => $online,
                'lastConn'    => $stopTime,
                'createdAt'   => $createdAt,
                'balance'     => $balance,
                'creditLimit' => $creditLimit,
                // 'usage'       => $usage,
                'status'      => $active,
                // 'status'      => $status,
                'imei'        => $localImei,
                // 'geo'         => $geo,
                'eventTime'   => $eventTime,
                'deviceImei'  => $deviceImei,
                'latitude'    => $lat,
                'longitude'   => $lng,
                'consumption' => $consumption
            ];

        }

        return $lines;
    }

    public function findCallerId($login, $callerId = null, $carrier = 0)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ApiShowUserCallerIds',
                'Company'    => $this->company,
                'Login'      => $login,
                'CallerId'   => $callerId,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            
            return null;
        }

        if  (empty($xml->response->return)) {
            return [];
        }

        $result = [];

        if (count($xml->response->return) == 1) {
            $data = (string) $xml->response->return;            

            list($login, $carrier, $callerid, $ip, $iccid, $monthlyPayment, $mrc, $online, $createdAt, $startTime, $balance,
                $creditLimit, $consumption, $status, $imei, $lat, $lng, $eventTime, $deviceImei , $stopTime, $desc) = explode('|', $data);

            $operadora = $this->getOperator($carrier);
            
            if ($status == 1024) { 
                $status = 'Ativo';
            } else {
                $status = 'Bloqueado';
            }

            if (strlen($createdAt) == 10) {
                $createdAt = now()->createFromFormat('Y-m-d', $createdAt)->format('d/m/Y');
            } else if (strlen($createdAt) == 19) {
                $createdAt = now()->createFromFormat('Y-m-d H:i:s', $createdAt)->format('d/m/Y');
            }

            if (strlen($stopTime) == 10) {
                $stopTime = now()->createFromFormat('Y-m-d', $stopTime)->format('d/m/Y');
            } else if (strlen($stopTime) == 19) {
                $stopTime = now()->createFromFormat('Y-m-d H:i:s', $stopTime)->format('d/m/Y H:i:s');
            }

            $balance = (float) number_format($balance, 2);
            
            $mrc = (float) $mrc;
            $consumption = 0;

            if ($mrc > 0) {
                $balance     = str_replace(',', '', $balance);
                $consumption = ((100 * ($mrc-$balance)) / $mrc);
            }

            $mrc = (!is_null($mrc) && !empty($mrc)) ? $mrc . ' MB' : null;

            $currentImei = !empty($imei) ? $imei : $deviceImei;

            $result[] = [
                'login'        => $login,
                'carrier'      => $carrier,
                'operadora'    => $operadora,
                'imei'         => $currentImei,
                'description'  => $desc,
                'callerid'     => $callerid,
                'ip'           => $ip,
                'iccid'        => $iccid,
                'latitude'     => $lat,
                'longitude'    => $lng,
                'mrc'          => $mrc,
                'monthlyPayment' => $monthlyPayment,
                'credit'       => $creditLimit,
                'balance'      => $balance,
                'consumption'  => $consumption . '%',
                'createdAt'    => $createdAt,
                'lastConn'     => $stopTime,
                'online'       => $online,
                'status'       => $status
            ];            
        } else {

            foreach ($xml->response->return as $item)
            {
                list($login, $carrier, $callerid, $ip, $iccid, $monthlyPayment, $mrc, $online, $createdAt, $startTime, $balance,
                    $creditLimit, $consumption, $status, $imei, $lat, $lng, $eventTime, $deviceImei, $stopTime, $desc) = explode('|', $item);

                $operadora = $this->getOperator($carrier);
                
                if ($status == 1024) { 
                    $status = 'Ativo';
                } else {
                    $status = 'Bloqueado';
                }
    
                if (strlen($createdAt) == 10) {
                    $createdAt = now()->createFromFormat('Y-m-d', $createdAt)->format('d/m/Y');
                } else if (strlen($createdAt) == 19) {
                    $createdAt = now()->createFromFormat('Y-m-d H:i:s', $createdAt)->format('d/m/Y');
                }
    
                if (strlen($stopTime) == 10) {
                    $stopTime = now()->createFromFormat('Y-m-d', $stopTime)->format('d/m/Y');
                } else if (strlen($stopTime) == 19) {
                    $stopTime = now()->createFromFormat('Y-m-d H:i:s', $stopTime)->format('d/m/Y H:i:s');
                }
    
                $balance = (float) number_format($balance, 2);
            
                $mrc = (float) $mrc;
                $consumption = 0;

                if ($mrc > 0) {

                    $consumption = ((100 * ($mrc-$balance)) / $mrc);
                }

                $mrc = (!is_null($mrc) && !empty($mrc)) ? $mrc . ' MB' : null;

                $currentImei = !empty($imei) ? $imei : $deviceImei;
    
                $result[] = [
                    'login'        => $login,
                    'carrier'      => $carrier,
                    'operadora'    => $operadora,
                    'imei'         => $currentImei,
                    'description'  => $desc,
                    'callerid'     => $callerid,
                    'ip'           => $ip,
                    'iccid'        => $iccid,
                    'latitude'     => $lat,
                    'longitude'    => $lng,
                    'mrc'          => $mrc,
                    'monthlyPayment' => $monthlyPayment,
                    'credit'       => $creditLimit,
                    'balance'      => $balance,
                    'consumption'  => $consumption,
                    'createdAt'    => $createdAt,
                    'lastConn'     => $stopTime,
                    'online'       => $online,
                    'status'       => $status,
                ];  
            }
        }

        return $result;
    }

    public function geo($callerid)
    {
        $response = $this->client->post($this->url, [
            'form_params' =>  [
                'Function'    => 'APIGetPINGeo',
                'Company'     => $this->company,
                'admin'       => 'admin',
                'CallerId'    => $callerid,
                'ServiceType' => 20,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ]
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }
        
        $result = $xml->response;

        return $result;
    }

    public function editPin($login, $carrier, $data)
    {
        $response = $this->client->post($this->url, [
            'form_params' =>  [
                'Function'    => 'EditAddrPin',
                'Company'     => $this->company,
                'Login'       => $login,
                'PIN'         => $data['callerid'],
                'PinCarrier'  => $carrier,
                'Credit'      => $data['credit'] ?? 0,
                'Balance'     => $data['balance'] ?? 0,
                'CheckImei'   => $data['checkimei'] ?? 0,
                'FirstName'   => $data['firstName'] ?? '',
                'Address1'    => $data['address1'] ?? '',
                'City'        => $data['city'] ?? '',
                'State'       => $data['state'] ?? '',
                'Zip'         => $data['zip'] ?? '',
                'Country'     => $data['country'] ?? '',
                'Zip'         => $data['zip'] ?? '',
                'Phone'       => $data['phone'] ?? '',
                'Description' => $data['description'] ?? '',
                'Imei'        => $data['imei'] ?? '',
                'Fence'       => 0,
                // 'Lat'      => $data['lat'],
                // 'Long'     => $data['long'],
                'ServiceType' => 20,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = $xml->response;

        return $result->errorcode;
    }

    public function editTrafficPin($login, $carrier, $callerId, $traffic)
    {
        $response = $this->client->post($this->url, [
            'form_params' =>  [
                'Function'    => 'EditTrafficPin',
                'Company'     => $this->company,
                'Login'       => $login,
                'PinCarrier'  => $carrier,
                'PIN'         => $callerId,
                'Traffic'     => $traffic,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response;

        return $result;
    }

    public function editCreditPin($login, $callerid, $carrier, $iccid, $credit)
    {
        $response = $this->client->post($this->url, [
            'form_params' =>  [
                'Function'    => 'EditCreditPin',
                'Company'     => $this->company,
                'Login'       => $login,
                'PIN'         => $callerid,
                'ICCID'       => $iccid,
                'PinCarrier'  => $carrier,
                'Credit'      => $credit,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response;

        return $result;
    }

    public function pinTotalOnline($login = 'estoque', $carrier = 0, $adv = 'yes')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ApiShowTotalOnline',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'Adv'        => $adv,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response->return;

        $total = 0;

        if ($carrier == 0) {
            
            foreach ($result as $string)
            {                
                list($count, $carrier, $operadora) = explode('|', $string);
                $total += $count;
            }

            return $total;
        }
      
        foreach ($result as $string)
        {
            list($total, $carrier, $operadora) = explode('|', $string);

            $color = '';

            switch($operadora) {
                case 'Claro' :
                    $color = '#B40404';
                    break;
                case 'PORTO' :
                    $color = '#0000FF';
                    break;
                case 'VIVO' :
                    $color = '#5F04B4';
                    break;
                case 'Parla' :
                    $color = '#EDA05E';
                    break;
                case 'SmartCenter' :
                    $color = '#555861';
                    break;
                case 'Vodafone' :
                    $color = '#FF0000';
                    break;
                case 'Tim' | 'TIM' :
                    $color = '#3104B4';
                    break;
                case 'Oi' | 'OI' :
                    $color = '#FFFF00';
                    break;
                    default: 
                case 'LEADM2M' :
                    $color = '#8089ff';
                    break;
            }

            $result = [
                'total'     => $total,
                'carrier'   => $carrier,
                'operadora' => $operadora,
                'color'     => $color
            ];
        }

        if (empty($result)) {
            $result = [
                'total' => 0
            ];
        }

        return $result;
    }

    public function pinTotalOffline($login = 'estoque', $carrier = 0)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'ApiShowTotalOffline',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'Adv'        => 'yes',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response->return;

        $total = 0;
        if ($carrier == 0)  {
            foreach ($result as $string)
            {
                list($count, $carrier, $operadora) = explode('|', $string);

                $total += $count;
            }
            
            return $total;
        }
      
        foreach ($result as $string)
        {
            list($total, $carrier, $operadora) = explode('|', $string);

            $result = [
                'total'     => $total,
                'carrier'   => $carrier,
                'operadora' => $operadora,
            ];
        }

        if (empty($result)) {
            $result = [
                'total' => 0
            ];
        }

        return $result;
    }

    public function neverConnectedPins($login = 'estoque', $carrier = 0, $adv = 'yes')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'NeverConnectedPins',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'Adv'        => $adv,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response->return;

        $total   = 0;
        $results = [];

        foreach ($result as $string)
        {
            if ($adv == 'yes') {

                $rs = explode('|', $string)[0];
                $total += $rs;
            } else {

                list($login, $callerid, $cid, $ip, $iccid, $lastConn, $carrier) = explode('|', $string);

                $results[] = [
                    'login' => $login,
                    'callerid' => $callerid,
                    'callerid' => $callerid,
                    'ip' => $iccid,
                    'lastConn' => $lastConn,
                ];
            }
        }

        return $adv == 'yes' ? $total : collect($results);
    }

    public function pinTotalBlocked($carrier, $login = 'estoque')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'BlockedPins',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response->return;

        $total  = 0;
      
        foreach ($result as $string)
        {
            $total++;
        }

        return $total;
    }

    public function totalPins($login, $pinCarrier, $startDate = null, $endDate = null, $hasChilds = false)
    {
        $full      = 'no';
        $hierarchy = 'no';

        if ($hasChilds) {
            $full = 'yes';
            $hierarchy = 'yes';
        }

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'ApiShowTotalServices',
                'Company'     => $this->company,
                'Admin'       => $this->admin,
                'AdminPwd'    => $this->adminPwd,
                'Login'       => $login,
                'PinCarrier'  => $pinCarrier,
                'ServiceType' => 20, // M2M
                'StartDate'   => $startDate,
                'EndDate'     => $endDate,
                'Hierarchy'   => $hierarchy,
                'Full'        => $full,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);
            
        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return [[
                'total'     => 0,
                'carrier'   => null,
                'operadora' => null,
            ]];
        }

        // $total = 0;
        // if ($pinCarrier == 0) {
        //     $result = (array) $xml->response->return;
        //     foreach ($result as $item)
        //     {
        //         list($count, $carrier, $operadora) = explode('|', $item);
        //         $total += $count;
        //     }
        //     return $total;
        // }

        $items  = [];
        $result = (array) $xml->response->return;

        foreach ($result as $item)
        {
            $color = '';
            list($total, $carrier, $operadora) = explode('|', $item);
            
            $total = (int) $total;
            if ($total == 0) {
                continue;
            }

            switch($operadora) {
                case 'Claro' :
                    $color = '#B40404';
                    break;
                case 'PORTO' :
                    $color = '#0000FF';
                    break;
                case 'VIVO' :
                    $color = '#5F04B4';
                    break;
                case 'Parla' :
                    $color = '#EDA05E';
                    break;
                case 'SmartCenter' :
                    $color = '#555861';
                    break;
                case 'Vodafone' :
                    $color = '#FF0000';
                    break;
                case 'Tim' | 'TIM' :
                    $color = '#3104B4';
                    break;
                case 'Oi' | 'OI' :
                    $color = '#FFFF00';
                    break;
                    default: 
                case 'LEADM2M' :
                    $color = '#8089ff';
                    break;
            }        
            $items[] = [
                'total'     => (int) $total,
                'carrier'   => $carrier,
                'operadora' => $operadora,
                'color'     => $color
            ];
        }

        if (empty($items)) {
            $items[]=[
                'total'     => 0,
                'carrier'   => '-',
                'operadora' => '-',
            ];
        }

        return $items;
    }

    public function apiHadConnection($login, $pinCarrier, $startDate = null, $endDate = null)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'ApiHadConnection',
                'Company'     => $this->company,
                'Admin'       => $this->admin,
                'AdminPwd'    => $this->adminPwd,
                'Login'       => $login,
                'PinCarrier'  => $pinCarrier,
                'ServiceType' => 20, // M2M
                // 'StartDate'   => $startDate,
                'EndDate'     => $endDate,
                'connectstartdate' => $startDate,
                'connectenddate'   => $endDate,
                'Adv'         => 'yes',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        if ($xml->response->errorcode == 142) {
            return 0;
        }

        $result = explode('|', (string) $xml->response->return);
        
        return reset($result);
    }

    public function getPinCarriers($login = null)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'GetPINCarriers',
                'Company'    => $this->company,
                'Admin'      => $this->admin,
                'AdminPwd'   => $this->adminPwd,
                'Login'      => $login,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = (array) $xml->response->carrier;

        $items = [];

        foreach ($result as $string)
        {
            list($carrier, $operadora, $type, $slug) = explode('|', $string);
            if ($operadora == 'parla' || $operadora == 'Parla') {
                continue;
            }

            $items[] = [
                'carrier'   => $carrier,
                'operadora' => $operadora,
                'slug'      => $slug,
                'type'      => $type,
            ];
        }

        return $items;
    }

    public function movePin($carrier, $callerid, $loginFrom, $loginDest, $data)
    {
        if (isset($data['tradein'])) {
            if (is_numeric($data['tradein'])) {
                $installDate = now()->addDays($data['tradein'])->format('Y-m-d');
            }

            if (validateDate($data['tradein'], 'Y-m-d')) {
                $installDate = now()->createFromFormat('Y-m-d', $data['tradein'])->format('Y-m-d');
            }
    
            if (validateDate($data['tradein'], 'd/m/Y')) {
                $installDate = now()->createFromFormat('Y-m-d', $data['tradein'])->format('Y-m-d');
            }
        } else {
            $installDate = now()->format('Y-m-d');
        }

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'     => 'MoveService',
                'Company'      => $this->company,
                'Admin'        => $this->admin,
                'Login'        => $loginFrom,
                'LoginDest'    => $loginDest,
                'ServiceIdTmp' => $callerid,
                'PinCarrier'   => $carrier,
                'IccidStart'   => $data['iccidstart'],
                'InstFee'      => $data['instfee'],
                'cancelFee'    => $data['cancelfee'] ?? '0.0',
                'Mrc'          => $data['mrc'] ?? '0.0',
                'Balance'      => $data['credit'] ?? '0.0',
                'InstallDate'  => $installDate,
                'Adv'          => $data['adv'] ?? 'no',
                'Traffic'      => 0,
                'ServiceType'  => 20,
                'SessionID'    => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);
        
        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        return $xml->response;
    }

    public function resetPinBalance($pinCarrier, $pinNumber, $credit, $resetPin = 'no')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'     => 'ResetPINBalance',
                'Company'      => $this->company,
                'PinCarrier'   => $pinCarrier,
                'Pin'          => $pinNumber,
                'Credit'       => $credit,
                'ResetPin'     => $resetPin,
                'SessionID'    => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        return $xml->response;
    }

    public function checkCallerIDExist($login, $callerId, $pinCarrier)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'checkPinCallerID',
                'Company'    => $this->company,
                'Login'      => $login,
                'CallerID'   => $callerId,
                'PinCarrier' => $pinCarrier,
                'NoTag'      => 'No',
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 55) {
            return false;
        }

        if ($xml->response->errorcode == 0) {
            return true;
        }
        
        return false;
    }

    public function existsIccid($login, $carrier, $iccid, $returnOwner = false)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'FindPinFromICCID',
                'Login' => $login,
                'iccid'   => $iccid,
                'pincarrier' => $carrier,
                'ServiceType' => 20,
                'Output_type' => 'xml',
                'Company' => $this->company,
                'SessionID' => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
    
        $exists = false;
        if ($xml->response->errorcode == 0) {
            $response = explode("|",$xml->response->value);
            $owner = array_pop($response);
            $callerid = array_first($response);
            $exists = ($login === $owner);

            if ($returnOwner == true) {
                return [
                    'exists' => $exists,
                    'callerid' => $callerid
                ];
            }
        }

        return $exists;
    }

    public function addPin($login, array $data)
    {
        // dd($login, $this->company, $data);
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'     => 'AddPin',
                'Company'      => $this->company,
                'Login'        => $login,
                'Traffic'      => $data['traffic'],
                'AdminNoBank'  => $data['adminnobank'],
                
                'Billing'      => $data['billing'],
                'CallerId'     => $data['callerid'],
                'Mrc'          => $data['mrc'],
                'Description'  => $data['description'],
                'InstallDate'  => $data['installdate'],
                
                'PinCarrier'   => $data['pincarrier'],
                'Balance'      => $data['balance'],
                'Credit'       => $data['credit'],
                'MonthlyPayment' => $data['monthlyPayment'],
                
                'ICCID'        => $data['iccid'],
                'IMSI'         => $data['imsi'],
                'InstFree'     => $data['instfee'],
                'CheckImei'    => $data['checkimei'],

                'ServiceType'  => 20,
                'SessionID'    => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        return $xml->response;
    }

    public function deletePin($login, $pinCarrier, $callerId)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'     => 'DelService',
                'Company'      => $this->company,
                'Login'        => $login,
                'PinCarrier'   => $pinCarrier,
                'ServiceId'    => $callerId,
                'SessionID'    => $this->getSession(),
                'ServiceType'  => 2,
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        return $xml->response;
    }

    public function editMenuTags($carrier)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'EditMenuTags',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'PinCarrier'  => $carrier,
                'menu'        => 'printallm2m',
                'Output_type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        return $xml->response->errorcode == 0 ? true : false;
    }

    public function listPlans($pinCarrier)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'     => 'APIListPlans',
                'Company'      => $this->company,
                'PinCarrier'   => $pinCarrier,
                'SessionID'    => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);
        
        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 2 || $xml->response->errorcode == 142) {
            
            return null;
        }

        $result = (array) $xml->response->return;
        $plans = [];

        foreach ($result as $res)
        {
            list($v0, $v1, $v2, $v3, $v4, $v5, $planName) = explode('|', $res);
            
            $plans[] = [
                'name'  => $planName,
                'value' => $res,
                'value00' => $v5,
            ];
        }
        
        return $plans;
    }

    public function editPinPlans($carrier, $data)
    {
        $body = [
            'Function'   => 'EditPinPlans',
            'Company'    => $this->company,
            'Login'      => 'admin',
            'Admin'      => 'admin',
            'PinCarrier' => $carrier,
            'SessionID'  => $this->getSession(),
            'Output_type' => 'xml'
        ];

        foreach ($data as $field => $value)
        {
            if (isset($data[$field])) {
                $body[$field] = $value;
            }
        }

        $response = $this->client->post($this->url, [
            'form_params' => $body,
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
    }

    public function no24($login, $carrier, $total = 1, $negative = 'yes', $adv = 'yes')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'No24HConnectionPins',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'total'      => $total,
                'Flow'       => $negative,
                'Adv'        => $adv,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);
        
        if ($xml->response->errorcode == 153) {
            return null;
        }

        $items  = [];
        $total  = 0;
        $result = $xml->response->return;

        foreach ($result as $string)
        {
            if ($adv == 'yes') {

                $rs = explode('|', $string);
                $total += (int) $rs[0];

            } else {

                list(
                    $company, $login, $callerid, $imei, $monthlyPayment, $monthlyPayment2, $mrc, 
                    $installedAt, $ip, $iccid, $consumption, $balance, $lastConn,
                    $v1,$v2,$v2,$status
                ) = explode('|',(string) $string[0]);

                if (strlen($lastConn) == 10) {
                    $lastConn = now()->createFromFormat('Y-m-d', $lastConn)->format('d/m/Y');
                }
                if (strlen($lastConn) == 19) {
                    $lastConn = now()->createFromFormat('Y-m-d H:i:s', $lastConn)->format('d/m/Y H:i:s');
                }
                    
                $items[] = [
                    'company' => $company,
                    'login' => $login,
                    'callerid' => $callerid,
                    'imei' => $imei,
                    'monthlyPayment' => $monthlyPayment,
                    'monthlyPayment2' => $monthlyPayment2,
                    'mrc' => $mrc,
                    'installedAt' => $installedAt,
                    'ip' => $ip,
                    'iccid' => $iccid,
                    'consumption' => $consumption,
                    'balance' => $balance,
                    'lastConn' => $lastConn,
                    'status' => $status,
                ];
            }
        }

        return $adv == 'yes' ? $total : collect($items);
    }

    public function getNo24ConnectionPins($login, $carrier = 0, $total = 1, $negative = 'yes', $adv = 'yes')
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'no24hconnectionpins',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'Adv'        => $adv,
                'Total'      => $total,
                'Flow'       => $negative,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = $xml->response->return;
        $total  = 0;
        $results = [];

        foreach ($result as $string)
        {
            if ($adv == 'yes') {

                $rs = explode('|', $string);
                $total += (int) $rs[0];
            } else {

                $string = (array) $string;

                list(
                    $company, $login, $callerId, $callerId2, $monthlyPayment, $creditLimit, $mcr, $createdAt, $ip, 
                    $iccid, $balance, $consumption, $lastConnection, $f0, $f1, $description, $status, $carrier, $carrierDesc) = explode('|', $string[0]);

                if (strlen($lastConnection) > 10) {
                    $lastConnection = now()->createFromFormat('Y-m-d H:i:s', $lastConnection)->format('d/m/Y');
                } else if (strlen($lastConnection) == 10)  {
                    $lastConnection = now()->createFromFormat('Y-m-d', $lastConnection)->format('d/m/Y');
                }

                $results[] = [
                    'login' => $login,
                    'callerid' => $callerId,
                    'ip' => $ip,
                    'iccid' => $iccid,
                    'lastConnection' => $lastConnection,
                    'balance' => $balance,
                    'creditLimit' => $creditLimit,
                    'description' => $description,
                    'carrier' => ucfirst(strtolower($carrierDesc)),
                    'operadora' => $this->getOperadora($carrier),
                ];
            }
        }

        return $adv == 'yes' ? $total : $results;
    }

    public function getLastConnectedPins($login, $carrier, $adv = 'yes', $total = 1)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'LastConnectedPins',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'ServiceType'=> 20,
                'Adv'        => $adv,
                'Total'      => $total,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }
        
        $total   = 0;
        $results = [];
        $result  = $xml->response->returns->return;

        foreach ($result as $string)
        {
            if ($adv == 'yes') {

                $rs = explode('|', $string)[0];
                $total += (int) $rs;
                
            } else {

                $results[] = [

                ];
            }
        }
        
        return $adv == 'yes' ? $total : $results;
    }

    public function getPinCdr($login, $carrier, $callerid, $startDate = null, $endDate = null)
    {
        if (is_null($startDate)) {
            $startDate = now()->startOfDay()->format('Y-m-d H:i:s');
        }

        if (is_null($endDate)) {
            $endDate = now()->endOfDay()->format('Y-m-d H:i:s');
        }
        
        $monthYear = now()->createFromFormat('Y-m-d H:i:s', $startDate)->format('m-Y');

        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'   => 'GetPinCDR',
                'Company'    => $this->company,
                'Login'      => $login,
                'PinCarrier' => $carrier,
                'Pin'        => $callerid,
                'StartDate'  => $startDate,
                'EndDate'    => $endDate,
                'MonthYear'  => $monthYear,
                'ServiceType'=> 20,
                'SessionID'  => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = $xml->response->cdr;

        $results = [];

        foreach ($result as $string)
        {
            list($originCallerId, $id, $destCallerId, $destination, $callStart, $callEnd, $duration, $cost, $traffic, $termination) = explode('|', $string);

            $callStart = now()->createFromFormat('Y-m-d H:i:s', $callStart)->format('d/m/Y H:i:s');
            $callEnd   = now()->createFromFormat('Y-m-d H:i:s', $callEnd)->format('d/m/Y H:i:s');
            $duration  = gmdate('H:i:s', $duration);

            $results[] = [
                'id'             => $id,
                'originCallerId' => $originCallerId,
                'destCallerId'   => $destCallerId,
                'destination'    => $destination,
                'callStart'      => $callStart,
                'callEnd'        => $callEnd,
                'duration'       => $duration,
                'cost'           => 'R$ ' .number_format($cost,2),
                'traffic'        => number_format($traffic, 2),
                'traffic_mb'     => number_format($traffic / 1000, 2),
                'termination'    => $termination
            ];
        }

        return $results;
    }

    public function getPinCdrCurl($login, $carrier, $callerid, $startDate = null, $endDate = null)
    {
        if (is_null($startDate)) {
            $startDate = now()->startOfDay()->format('Y-m-d H:i:s');
        }

        if (is_null($endDate)) {
            $endDate = now()->endOfDay()->format('Y-m-d H:i:s');
        }
        
        $monthYear = now()->createFromFormat('Y-m-d H:i:s', $startDate)->format('m-Y');

        try {

            $company = $this->company;
            $session = $this->getSession();

            $url = $this->url . "?Function=GetPinCDR&company=${company}&login=${login}&pincarrier=${carrier}&pin=${callerid}&startdate=${startDate}&enddate=${endDate}&monthyear=${monthYear}&servicetype=20&sessionid=${session}";

            $response = $this->client->request('GET', $url,
                [
                    'timeout' => 59,
                    'connect_timeout' => 59,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            );

            $contents = $response->getBody()->getContents();
            $xml = $this->loadXML($contents);
    
            if ($xml->response->errorcode == 153) {
                return null;
            }
    
            $result = $xml->response->cdr;
    
            $results = [];
    
            foreach ($result as $string)
            {
                list($originCallerId, $id, $destCallerId, $destination, $callStart, $callEnd, $duration, $cost, $traffic, $termination) = explode('|', $string);
    
                $callStart = now()->createFromFormat('Y-m-d H:i:s', $callStart)->format('d/m/Y H:i:s');
                $callEnd   = now()->createFromFormat('Y-m-d H:i:s', $callEnd)->format('d/m/Y H:i:s');
                $duration  = gmdate('H:i:s', $duration);
    
                $results[] = [
                    'id'             => $id,
                    'originCallerId' => $originCallerId,
                    'destCallerId'   => $destCallerId,
                    'destination'    => $destination,
                    'callStart'      => $callStart,
                    'callEnd'        => $callEnd,
                    'duration'       => $duration,
                    'cost'           => 'R$ ' .number_format($cost,2),
                    'traffic'        => number_format($traffic,2),
                    'traffic_kb'     => number_format($traffic * 1024,2),
                    'termination'    => $termination
                ];
            }
    
            return collect($results);

        } catch (\Exception $e) {

            $body = "Ocorreu um erro de timeout na api GetPinCDR após uma tentativa de execução de 59 segundos. \n";
            $body .= "url: " . $url . "\n\n";
            $body .= "Sugestão: Adicionar paginação para esta chamada de api. ini=1 (página inicial). total=15 (total de itens por página).";

            Mail::raw($body, function($m) {
                $m->from('contato@allcomtelecom.com');
                $m->to('clovis@parlacom.net');
                $m->subject('Erro de timout na chamada da api GetPinCDR.');
            });

            return [];
        }
    }

    public function apiM2MSummary($login, $carrier, $startDate)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'ApiM2mSummary',
                'Company'     => $this->company,
                'Login'       => $login,
                'PinCarrier'  => $carrier,
                'StartDate'   => $startDate,
                'ServiceType' => 20,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $result = [];
        $response = (array) $xml->response->return;

        //header
        //login|carrier|carriercost|totalcards|depleted|negative|overage|blocked|totaldata|grandtotal|1day_on|1day_off|30day_on|30day_off|60day_on|60day_off|90day_on|90day_off|180day_on|180day_off

        foreach ($response as $item)
        {
            list(
                $login, $carrier, $carriercost, $totalcards, $depleted, $negative, $overage, $blocked, 
                $totaldata, $grandtotal, 
                $day1off, $day1on, $day30off, $day30on, $day60off, $day60on,
                $day90off, $day90on, $day180off, $day180on, 
            ) = explode('|', $item);
            
            $result[] = [
                'login' => $login,
                'carrier' => $carrier,
                'carriercost' => $carriercost,
                'totalcards' => $totalcards,
                'depleted' => $depleted,
                'negative' => $negative,
                'overage' => $overage,
                'blocked' => $blocked,
                'totaldata' => $totaldata,
                'grandtotal' => $grandtotal,
                'day1on' => $day1on,
                'day1off' => $day1off,
                'day30on' => $day30on,
                'day30off' => $day30off,
                'day60on' => $day60on,
                'day60off' => $day60off,
                'day90on' => $day90on,
                'day90off' => $day90off,
                'day180on' => $day180on,
                'day180off' => $day180off,
            ];
        }

        return collect($result);
    }

    public function financialBlock($login, $carrier, $traffic)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'BlockLogin',
                'Company'     => $this->company,
                'Login'       => $login,
                'PinCarrier'  => $carrier,
                'Traffic'     => $traffic,
                'All'         => 'yes',
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (string) $xml->response->errorcode;

        return $response == 0 ? true : false;        
    }

    public function addSMSCredit($login, $loginDest)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'TransferSMSBank',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'Login'       => $login,
                'LoginDest'   => $loginDest,
                'Value'       => $value,
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (array) $xml->response->return;
    }

    public function buySmsBank($login, $value)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'BuySmsBank',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'Login'       => $login,
                'Total'       => $value,
                'PinCarrier'  => 1,
                'Adv'         => 'yes',
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (array) $xml->response->return;
    }
    
    public function getSmsBank($login)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'getsmsbank',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'Login'       => $login,
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (float) $xml->response->balance;

        return $response;
    } 

    public function sendUserSms($login, $destination, $text)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'SendUserSMS',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'LoginDest'   => $login,
                'Destination' => $destination,
                'Carrier'     => 7, //Brasil
                'Text'        => $text,
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (array) $xml->response->return;
    }

    public function searchZip($login, $zip, $speed)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'SearchZipCoverage',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'Login'       => $login,
                'address1'    => $zip,
                'speed'       => $speed,
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }

        $response = (array) $xml->response->return;

        $results = [];
        foreach ($response as $row)
        {
            list($quantitativa, $operadora, $cobertura, $r0, $qualidade) =  explode("|", $row);

            $results[] = [
                'quantitativa' => $quantitativa,
                'operadora' => $operadora,
                'cobertura' => $cobertura,
                'qualidade' => $qualidade,
            ];
        }

        return collect($results);
    }

    public function dailyBalance($login, $carrier)
    {
        $response = $this->client->post($this->url, [
            'form_params' => [
                'Function'    => 'GetDailyBalance',
                'Company'     => $this->company,
                'Admin'       => 'admin',
                'Login'       => $login,
                'Carrier'     => $carrier,
                'StartDate'   => '2019-06-20',
                'EndDAte'     =>  '2019-06-21',
                'Output_Type' => 'xml',
                'SessionID'   => $this->getSession(),
                'Output_type' => 'xml'
            ],
            'headers' => $this->getHeaders()
        ]);

        $contents = $response->getBody()->getContents();
        $xml = $this->loadXML($contents);

        if ($xml->response->errorcode == 153) {
            return null;
        }
        dd($xml);

        $response = (array) $xml->response->return;
        dd($response);

    }

    public function getOperator($carrier)
    {
        switch($carrier) {

            case 33 :
                $carrier = 'Algar';
                break;
            case 333 :
                $carrier = 'Algar PVT';
                break;
            case 19 :
                $carrier = 'Vodafone IpSec';
                break;
            case 12 :
                $carrier = 'Porto';
                break;
            case 10 :
                $carrier = 'Lead M2M';
                break;
            case 20 :
                $carrier = 'Vivo';
                break;    
            case 50 :
                $carrier = 'Claro';
                break;
            case 55 :
                $carrier = 'Claro BL';
                break;
            case 7 :
                $carrier = 'SmartCenter';
                break;
            case 70 :
                $carrier = 'Sierra';
                break;
            case 77 :
                $carrier = 'Vivo BL';
                break;
            case 22 :
                $carrier = 'Vivo WL';
                break;
            case 3 :
                $carrier = "Algar 3OP";
                break;
        }

        return $carrier;
    }

    public function getCarrier($carrier)
    {
        switch($carrier) {

            case 'Algar' :
                $carrier = 33;
                break;
            case 'Algar PVT' :
                $carrier = 333;
                break;
            case 'Vodafone IpSec' :
                $carrier = 19;
                break;
            case 'Porto' :
                $carrier = 12;
                break;
            case 'Lead M2M' :
                $carrier = 10;
                break;
            case 'Vivo' :
                $carrier = 20;
                break;    
            case 'Claro' :
                $carrier = 50;
                break;
            case 'Claro BL' :
                $carrier = 55;
                break;
            case 'SmartCenter' :
                $carrier = 7;
                break;
            case 'Sierra' :
                $carrier = 70;
                break;
            case 'Vivo BL' :
                $carrier = 77;
                break;
            case "Algar 3OP":
                $carrier = 3;
                break;
        }

        return $carrier;
    }

    public function getMcc($code)
    {
        $mcc = '';
        switch ($code) {
            default :
            case '724':
                $mcc = 'Brasil';
                break;
        }

        return $mcc;
    }

    public function getMnc($code)
    {
        $mnc = '';
        switch ($code) {
            case '1':
            case '6':
            case '10':
            case '11':
            case '23':
                $mnc = 'Vivo';
                break;
            case '5':
            case '12':
            case '38':
                $mnc = 'Claro';
                break;
            case '32':
            case '33':
            case '34':
                $mnc = 'CTBC';
                break;
            
            case '2':
            case '3':
            case '4':
            case '8':
                $mnc = 'TIM';
                break;
            
            case '39':
            case '00':
                $mnc = 'Nextel';
                break;
            
            case '30':
            case '31':
                $mnc = 'Oi';
                break;
        }

        return $mnc;
    }

    public function getSessionID()
    {
        return $this->cache->get('session');
    }
}
