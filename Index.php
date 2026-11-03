<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatore BMI</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #F1F3F5;
            color: #1F2328;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .box {
            background-color: #ffffff;
            width: 360px;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d0d7de;
            border-radius: 6px;
            font-size: 14px;
        }

        .errore-msg {
            color: #B32D2D;
            font-size: 12px;
            margin-top: 6px;
            display: block;
            font-weight: bold;
        }

        button {
            width: 100%;
            background-color: #1F2328;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
        }

        button:hover {
            opacity: 0.9;
        }

        .card {
            border-width: 3px;
            border-style: solid;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .bmi-valore {
            font-size: 48px;
            font-weight: bold;
            margin: 12px 0;
        }

        .fascia-testo {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .sottopeso {
            background-color: #E3EEF9;
            border-color: #2F6FB3;
            color: #2F6FB3;
        }

        .normopeso {
            background-color: #E3F4E6;
            border-color: #256D34;
            color: #256D34;
        }

        .sovrappeso {
            background-color: #FCEBD7;
            border-color: #9A4F06;
            color: #9A4F06;
        }

        .obesita {
            background-color: #FBE2E2;
            border-color: #B32D2D;
            color: #B32D2D;
        }

        .link-calcola {
            display: inline-block;
            color: #1F2328;
            text-decoration: none;
            font-weight: bold;
        }

        .link-calcola:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php
    $errori = [];
    $calcolo_completato = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nome = isset($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : '';
        $peso = isset($_POST['peso']) ? (float)$_POST['peso'] : 0;
        $altezza_cm = isset($_POST['altezza']) ? (float)$_POST['altezza'] : 0;

        if (empty($nome)) {
            $errori['nome'] = "Valore non valido";
        }
        if ($peso <= 0) {
            $errori['peso'] = "Valore non valido";
        }
        if ($altezza_cm <= 0) {
            $errori['altezza'] = "Valore non valido";
        }

        if (empty($errori)) {
            $calcolo_completato = true;
            $altezza_m = $altezza_cm / 100;
            
            $bmi = $peso / ($altezza_m * $altezza_m);

            if ($bmi < 18.5) {
                $classe = 'sottopeso';
                $fascia = 'Sottopeso';
            } elseif ($bmi <= 24.9) {
                $classe = 'normopeso';
                $fascia = 'Normopeso';
            } elseif ($bmi <= 29.9) {
                $classe = 'sovrappeso';
                $fascia = 'Sovrappeso';
            } else {
                $classe = 'obesita';
                $fascia = 'Obesità';
            }

            $log = fopen("log.csv", "a") or die("Non puoi entrare");

            if($log == TRUE){
                $record = "";
                $record .= date("d/m/Y") . ";";
                $record .= date("G:i:s") . ";";
                $record .= $_SERVER['REMOTE_ADDR'] . ";";
                $record .= $nome . ";";
                $record .= $peso . ";";
                $record .= $altezza_cm . ";";
                $record .= $bmi . ";";
                $record .= $fascia . ";\n";
                
                fwrite($log, $record);
                fclose($log);
            }
        }
    }
    
    if ($calcolo_completato): 
    ?>
        <div class="box">
            <div class="card <?php echo $classe; ?>">
                <h2><?php echo $nome; ?></h2>
                <p>Peso: <?php echo $peso; ?> kg | Altezza: <?php echo $altezza_cm; ?> cm</p>
                <div class="bmi-valore"><?php echo number_format($bmi, 2, ',', ''); ?></div>
                <div class="fascia-testo"><?php echo $fascia; ?></div>
            </div>
            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="link-calcola">Calcola di nuovo</a>
        </div>

    <?php 
    else: 
    ?>
        
        <div class="box">
            <h1>Calcolatore BMI</h1>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
                    <?php if(isset($errori['nome'])): ?>
                        <span class="errore-msg"><?php echo $errori['nome']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" id="peso" name="peso" step="0.1" value="<?php echo isset($_POST['peso']) ? htmlspecialchars($_POST['peso']) : ''; ?>" required>
                    <?php if(isset($errori['peso'])): ?>
                        <span class="errore-msg"><?php echo $errori['peso']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="altezza">Altezza (cm)</label>
                    <input type="number" id="altezza" name="altezza" value="<?php echo isset($_POST['altezza']) ? htmlspecialchars($_POST['altezza']) : ''; ?>" required>
                    <?php if(isset($errori['altezza'])): ?>
                        <span class="errore-msg"><?php echo $errori['altezza']; ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit">Calcola</button>
            </form>
        </div>

    <?php 
    endif; 
    ?>

</body>
</html>