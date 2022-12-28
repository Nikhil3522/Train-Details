<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            background-color: lightblue;

        }
        form{
            display: flex;
            flex-direction: column;
            width: 250px;
            font-size: large;
        }

        .div-center {
            display:flex;
            justify-content: center;
        }

        .button-20 {
        appearance: button;
        background-color: #4D4AE8;
        background-image: linear-gradient(180deg, rgba(255, 255, 255, .15), rgba(255, 255, 255, 0));
        border: 1px solid #4D4AE8;
        border-radius: 1rem;
        box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset,rgba(46, 54, 80, 0.075) 0 1px 1px;
        box-sizing: border-box;
        color: #FFFFFF;
        cursor: pointer;
        display: inline-block;
        font-family: Inter,sans-serif;
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.5;
        margin: 0;
        padding: .5rem 1rem;
        text-align: center;
        text-transform: none;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        user-select: none;
        -webkit-user-select: none;
        touch-action: manipulation;
        vertical-align: middle;
        }

        .button-20:focus:not(:focus-visible),
        .button-20:focus {
        outline: 0;
        }

        .button-20:hover {
        background-color: #3733E5;
        border-color: #3733E5;
        }

        .button-20:focus {
        background-color: #413FC5;
        border-color: #3E3BBA;
        box-shadow: rgba(255, 255, 255, 0.15) 0 1px 0 inset, rgba(46, 54, 80, 0.075) 0 1px 1px, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
        }

        .button-20:active {
        background-color: #3E3BBA;
        background-image: none;
        border-color: #3A38AE;
        box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset;
        }

        .button-20:active:focus {
        box-shadow: rgba(46, 54, 80, 0.125) 0 3px 5px inset, rgba(104, 101, 235, 0.5) 0 0 0 .2rem;
        }

        .button-20:disabled {
        background-image: none;
        box-shadow: none;
        opacity: .65;
        pointer-events: none;
        }

        input[type="text"]{
            height: 40px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="div-center">
        <div><a href='pnr.php' class="button-20">PNR status </a></div>
        <form action="index.php" method="GET">
            <input type="text" name="train_number" placeholder="Enter Train Number or Train Name..." />
            <input type="submit" class="button-20" value="Search" />
        </form>
    </div>

    <?php
        error_reporting(E_ERROR | E_PARSE);
        $train = $_GET['train_number'];

        if($train != NULL){
            
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://trains.p.rapidapi.com/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\r\n    \"search\": \"$train\"\r\n}",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: trains.p.rapidapi.com",
                    "X-RapidAPI-Key: 7e5e5af3ecmsh085c993a9bc40d7p10e1a0jsnca89f109767e",
                    "content-type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            $data = json_decode($response, true);
            // echo $response;

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                foreach ((array) $data as $value) {
                ?>
                    <div class="div-center">
                        <div>
                            <h2><?php echo $value['name'] ?> (<?php echo $value['train_num'] ?>)</h2>
                            <div class="div-center">
                                <div>
                                    <h3><?php echo $value['train_from'] ?>  &nbsp ==>  &nbsp <?php echo $value['train_to'] ?></h3>
                                    <h3>Depart Time <?php echo $value['data']['departTime'] ?> </h3>
                                    <h3>Arrive Time <?php echo $value['data']['arriveTime'] ?></h3>
                                    <?php 
                                        echo "<h3> Available Days ( ";
                                        foreach((array) $value['data']['days'] as $x => $d){
                                            if($d == 1){
                                                echo "$x, ";
                                            }
                                           
                                        }
                                        echo ")</h3>";
                                        ?>
                                </div>
                            <div>
                        </div>
                    </div>
                <?php
                }
            }
        }
        ?>
</body>
</html>