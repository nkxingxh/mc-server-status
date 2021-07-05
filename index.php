<?php
require_once "status.php";
if (empty($_REQUEST['server'])) {
    $mcserver = "xcraft.xyunmc.cf";
} else {
    $mcserver = $_REQUEST['server'];
}

$port = empty($_REQUEST['port']) ? 25565 : ((int) $_REQUEST['port']);
$res = empty($_REQUEST['query']) ? MCPing($mcserver, $port) : MCQuery($mcserver, $port);

if (!empty($_REQUEST['json'])) {
    echo json_encode($res);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow, noarchive" />
    <title><?php echo $mcserver; ?> &middot; Minecraft Server Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="search" type="application/opensearchdescription+xml" title="<?php echo $mcserver; ?> &middot; Minecraft Server Status" href="http://mcsrvstat.us/opensearchdescription.xml" />
    <link rel="icon" type="image/png" href="/img/minecraft.png" />
    <style>
        .minecraft {
            background-color: black;
            color: #AAAAAA;
            display: inline-block;
            font-family: monospace;
            letter-spacing: 0.1em;
            padding: 0.5em;
            white-space: pre;
        }

        table {
            overflow: hidden;
        }

        tr td:first-child {
            font-weight: bold;
            width: 15%;
        }

        #players img {
            margin: 0 0.2em 0.2em 0;
        }

        .badge {
            padding: 0.5em;
        }
    </style>
</head>

<body>
    <div class="container">
        <!--div class="alert alert-info mt-4" role="alert"><strong>INFO:</strong> This site is ad-free, and I would like to keep it that way. Please consider <a class="alert-link" href="https://paypal.me/spirit55555">donating</a> to keep it running. Thanks :)</div-->
        <header class="d-flex my-4">
            <a href="">
                <!--img class="d-none d-sm-block" src="<?php echo $res['favicon']; ?>" alt="Server icon for <?php echo $mcserver; ?>" height="64" width="64" /-->
            </a>
            <div class="ml-3">
                <h1>
                    <!--a href="/" class="text-dark"><?php echo $mcserver; ?></a-->Minecraft Server Status
                </h1>
                <!--p class="h5 text-muted">快速获取 Minecraft 服务器的信息</p-->
            </div>
        </header>
        <form action="" method="post" role="form">
            <div class="form-group">
                <label for="address" class="sr-only">Server address</label>
                <div class="input-group input-group-lg">
                    <input type="text" name="server" id="server" placeholder="hypixel.net / mc.hypixel.net / 172.65.128.35 / 172.65.128.35:25565" required class="form-control" value="<?php echo $mcserver; ?>" />
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">查询</button>
                    </div>
                </div>
                <div class="clearfix mt-1">
                    <!--label class="float-left"><input type="checkbox" name="bedrock" /> Bedrock server?</label-->
                    <p class="float-right small text-muted"><strong>Minecraft (Java) 1.7+</strong>, or servers with <code>enable-query=true</code>, are supported.</p>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-borderless">
                <tr>
                    <td>图标</td>
                    <td><img class="d-none d-sm-block" src="<?php echo $res['favicon']; ?>" alt="Server icon for <?php echo $mcserver; ?>" height="64" width="64" /></td>
                </tr>
                <tr>
                    <td><abbr title="Message of the Day">MOTD</abbr></td>
                    <td>
                        <span class="minecraft"><?php echo $res['motd_html']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>信息</td>
                    <td><span class="minecraft"><?php
                                                $n = is_array($res['sample_player_list']) ? count($res['sample_player_list']) : 0;
                                                for ($i = 0; $i < $n; $i++) {
                                                    echo motd2html($res['sample_player_list'][$i]['name']) . "<br>";
                                                }
                                                ?></span></td>
                </tr>
                <tr>
                    <td>Players</td>
                    <td><?php echo $res['players']; ?> / <?php echo $res['max_players']; ?></td>
                </tr>
                <tr>
                    <td>版本</td>
                    <td><?php echo $res['version']; ?></td>
                </tr>
                <tr id="debug">
                    <td>调试信息</td>
                    <td>
                        <dl class="row">
                            <dt class="col-sm-4 col-lg-3">主机名:</dt>
                            <dd class="col-sm-8 col-lg-9"><span class="badge badge-info"><?php echo $res['hostname']; ?></span></dd>
                            <dt class="col-sm-4 col-lg-3">IP 地址:</dt>
                            <dd class="col-sm-8 col-lg-9"><span class="badge badge-info"><?php echo $res['address']; ?></span></dd>
                            <dt class="col-sm-4 col-lg-3">端口:</dt>
                            <dd class="col-sm-8 col-lg-9"><span class="badge badge-info"><?php echo $res['port']; ?></span></dd>
                            <dt class="col-sm-4 col-lg-3">协议版本:</dt>
                            <dd class="col-sm-8 col-lg-9"><span class="badge badge-info"><?php echo $res['protocol']; ?></span></dd>
                            <!--dt class="col-sm-4 col-lg-3">Cached result</dt>
                            <dd class="col-sm-8 col-lg-9">
                                <span class="badge badge-success">Yes</span>
                            </dd-->
                            <dt class="col-sm-4 col-lg-3"><abbr title="Service record">SRV</abbr> 记录</dt>
                            <dd class="col-sm-8 col-lg-9">
                                <span class="badge badge-warning"><?php echo $res['srv'] ? "Yes" : "No"; ?></span>
                            </dd>
                            <dt class="col-sm-4 col-lg-3">Ping</dt>
                            <dd class="col-sm-8 col-lg-9">
                                <span class="badge badge-success">Yes</span>
                            </dd>
                            <dt class="col-sm-4 col-lg-3">Query</dt>
                            <dd class="col-sm-8 col-lg-9">
                                <span class="badge badge-danger">No</span>
                            </dd>
                        </dl>
                    </td>
                </tr>
            </table>
        </div>
        <hr />
        <p class="float-left text-muted">
            Copyright &copy; <?php echo date("Y"); ?> <a href="https://www.nkxingxh.top">NKXingXh</a></p>
        <!--nav class="float-right text-muted">
            <a href="/faq">FAQ</a> - <a href="https://paypal.me/spirit55555">Donate</a> - <a href="/about">About</a> - <a href="https://status.mcsrvstat.us">System status</a> - <a href="https://api.mcsrvstat.us">API</a>
        </nav-->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
</body>

</html>