<?php
$pdo = new PDO('sqlite:db/database');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$configs = iterator_to_array(include __DIR__."/generatePoints.php");
?>

<!doctype html>
<html lang="en" style="font-size:73%;">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">

    <style>
        @media print {
            body {
                width: 21cm;
                height: 29.7cm;
                margin: 0mm 0mm 0mm 0mm;
                margin-top: 0 !important;
                /* change the margins as you want them to be. */
            }
        }

        .card {
            margin: auto;
            margin-bottom: 1px;
            width:21rem;
            height:29.7rem
        }

        .card-body {
            padding-top: 1px;
        }

        h5 {
            margin-bottom: 0px;
        }

        .col {
            padding: 0px;
        }

        @font-face {
            font-family: "pokemon";
            src: url('Pokemon Hollow.ttf');
        }
    </style>
    <title>PokeMath</title>
</head>
<body style="font-family: 'Roboto', sans-serif;">
<div class="container-fluid">
    <?php
    $nbPerRow = 4;
    $currentRow = 0;
    $currentCol = 0;
    $paramLevel = intval($_GET['l'] ?? 1);
    foreach ($pdo->query("SELECT pokemon.*, base.id as base_id, base.name as base_name from pokemon
                JOIN pokemon AS base ON base.id = CAST(SUBSTR(pokemon.path,2,3) AS decimal)
                WHERE pokemon.level = $paramLevel ORDER BY pokemon.path"
    ) as $pokemon) {

        if ($currentCol === 0) {
            ?><div class="row align-items-center justify-content-center"><?php
        }
        $strId = str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT);
        --$pokemon['level'];

        [$defense, $attack1, $attack2, $attack3, $pv] = $configs[($pokemon['rank']-1) % count($configs)];
        $offset = 2 * $pokemon['level'];
        [$defense, $attack1, $attack2, $attack3, $pv] = [$defense + $offset, $attack1 + $offset, $attack2 + $offset, $attack3 + $offset, $pv + $offset];

        ?>
        <div class="col">
            <div class="card border-dark mb-3">
                <div class="card-header bg-transparent">
                    <h5>
                        <span style="font-family: 'Open Sans', sans-serif;"><?= $pokemon['name'] ?></span>
                        <small class="text-right text-muted"></small>
                        <span class="text-right float-right">
                            <small style='font-family: "pokemon"'>PoKéMaTh</small><small> #<?= $strId ?></small>
                            </span>

                    </h5>
                </div>

                <img style="width:55%" src="<?= "images/$strId.png" ?>" class="card-img-top mx-auto"
                     alt="<?= $pokemon['name'] ?>">
                <small class="text-center">
                    <em>
                        <?= $pokemon['level'] ? ("{$pokemon['base_name']} / Niveau {$pokemon['level']} ") : 'Base' ?>
                    </em>
                </small>
                <div class="card-body">

                    <hr>
                    <div class="row">
                        <div class="offset-md-1 col-6 text-left">PV</div>
                        <div class="col-5"><?= $pv ?></div>

                        <div class="offset-md-1 col-6 text-left">Défense</div>
                        <div class="col-3"><?= $defense ?></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="offset-md-1 col-6 text-left"><?= $pokemon['move1'] ?></div>
                        <div class="col-3"><?= $attack1 ?></div>

                        <div class="offset-md-1 col-6 text-left"><?= $pokemon['move2'] ?></div>
                        <div class="col-3"><?= $attack2 ?></div>

                        <div class="offset-md-1 col-6 text-left"><?= $pokemon['move3'] ?></div>
                        <div class="col-3"><?= $attack3 ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $currentCol = ($currentCol + 1) % $nbPerRow;
        if ($currentCol === 0) {
            ?></div><?php
            $currentRow = ($currentRow + 1) %$nbPerRow;
            if($currentRow === 0) {?>
                <div style="page-break-after:always"></div>

                <?php
                // Back face
                    for($row =0;$row<$nbPerRow; ++$row) {?>
                    <div class="row align-items-center justify-content-center">
                        <?php for($col=0;$col<$nbPerRow; ++$col) {?>
                        <div class="col">
                            <div class="card border-dark mb-3  align-middle text-center">
                                    <div class="" style="width: 90%; top: 45%; margin: 0 auto; position: relative;">
                                        <h1 style='font-family: "pokemon"'>
                                            PoKéMaTh
                                        </h1>
                                    </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <?php } ?>
                    <div style="page-break-after:always"></div>
    <?php
            }
        }
    }
    ?>
</div>
</div>

</body>
</html>


