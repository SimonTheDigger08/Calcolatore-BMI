<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>

    <?php
    // Lettura dei dati dalla richiesta POST
    $nome = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '';
    $peso = isset($_POST['peso']) ? (float)$_POST['peso'] : 0;
    $altezza_cm = isset($_POST['altezza']) ? (float)$_POST['altezza'] : 0;

    // Inizializzazione variabili
    $bmi = 0;
    $classe = '';
    $fascia = '';

    if ($peso > 0 && $altezza_cm > 0) {
        // Conversione altezza da cm a metri
        $altezza_m = $altezza_cm / 100;
        
        // Calcolo del BMI: peso / (altezza in metri)^2
        $bmi = $peso / ($altezza_m * $altezza_m);

        // Determinazione della fascia e della classe CSS
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
    }
    ?>


        <div class="box">
            <?php if ($peso > 0 && $altezza_cm > 0): ?>
                <div class="card <?php echo $classe; ?>">
                    <h2><?php echo $nome; ?></h2>
                    <p>Peso: <?php echo $peso; ?> kg | Altezza: <?php echo $altezza_cm; ?> cm</p>
                    <div class="bmi-valore"><?php echo number_format($bmi, 2, ',', ''); ?></div>
                    <div class="fascia-testo"><?php echo $fascia; ?></div>
                </div>
            <?php else: ?>
                <p>Dati non validi o mancanti.</p>
            <?php endif; ?>

            <a href="form.html" class="link-calcola">Calcola di nuovo</a>
        </div>

</body>
</html>