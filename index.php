<?php
$pdo = new PDO('sqlite:db/database');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        @media print {
            body {
                width: 21cm;
                height: 29.7cm;
                margin: 5mm 5mm 5mm 5mm;
                /* change the margins as you want them to be. */
            }
        }
    </style>
    <title>PokeMath</title>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php
        foreach ($pdo->query("SELECT pokemon.*, base.id as base_id, base.name as base_name from pokemon
                JOIN pokemon AS base ON base.id = CAST(SUBSTR(pokemon.path,2,3) AS decimal)"
        ) as $pokemon) {
            $strId = str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT);
            ?>
            <div class="">
                <div class="card border-dark mb-3" style="width:21rem; height:29.7rem">
                    <div class="card-header bg-transparent">
                        <h5>
                            <?= $pokemon['name'] ?>
                            <small class="text-right text-muted float-right">#<?= $strId ?></small>
                        </h5>
                        <small><em>Niveau <?= $pokemon['level'] ?>
                                <?= $pokemon['level'] != 1 ? ("( Base {$pokemon['base_name']} )") : '' ?></em></small>
                    </div>
                    <img style="width:50%" src="<?= "images/$strId.png" ?>" class="card-img-top mx-auto"
                         alt="<?= $pokemon['name'] ?>">
                    <div class="card-body">

                        <hr>
                        <div class="row">
                            <div class="col-6 text-right">PV</div>
                            <div class="col-6">10</div>

                            <div class="col-6 text-right">Défense</div>
                            <div class="col-6">10</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 text-right">Attaque 1</div>
                            <div class="col-6">10</div>

                            <div class="col-6 text-right">Attaque 1</div>
                            <div class="col-6">10</div>

                            <div class="col-6 text-right">Attaque 1</div>
                            <div class="col-6">10</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
</html>


