<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descricao = $_POST["descricao"];

    // Salvar imagem
    $target_dir = "imagens/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }
    $imagem = $target_dir . basename($_FILES["imagem"]["name"]);
    move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagem);

    // Contar PCs existentes
    $contador = count(glob("pc*.html")) + 1;
    $arquivoPc = "pc$contador.html";

    // Seleção de vídeos baseada no título
    $tituloLower = strtolower($titulo);
    $videos = [];

    if (strpos($tituloLower, "rx 580") !== false) {
        $videos = [
            "https://www.youtube.com/embed/xa2iT7EbyXg",
            "https://www.youtube.com/embed/YOdI6v6lGns",
            "https://www.youtube.com/embed/0zR3wCPO7WQ"
        ];
    } elseif (strpos($tituloLower, "rtx 3060") !== false) {
        $videos = [
            "https://www.youtube.com/embed/0DEAzPj5pMg",
            "https://www.youtube.com/embed/k8S9kuS6C5g",
            "https://www.youtube.com/embed/1Yg6w5dDo4g"
        ];
    } elseif (strpos($tituloLower, "ryzen 5") !== false) {
        $videos = [
            "https://www.youtube.com/embed/q7y_EsE-wKY",
            "https://www.youtube.com/embed/L3OlmJuw2xA",
            "https://www.youtube.com/embed/VpCWT6TAVV0"
        ];
    } else {
        $videos = [
            "https://www.youtube.com/embed/4NRXx6U8ABQ",
            "https://www.youtube.com/embed/dQw4w9WgXcQ",
            "https://www.youtube.com/embed/3JZ_D3ELwOQ"
        ];
    }

    shuffle($videos);
    $video1 = $videos[0];
    $video2 = $videos[1];

    // Criar página do PC com layout atualizado
    $pagina = "
    <!DOCTYPE html>
    <html lang='pt-BR'>
    <head>
        <meta charset='UTF-8'>
        <title>$titulo</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
        <header>
            <h1>$titulo</h1>
        </header>
        <main class='pc-detalhes'>
            <img src='$imagem' alt='$titulo'>
            <div class='descricao'>$descricao</div>
            <h2>Testes no YouTube</h2>
            <div class='videos'>
                <iframe src='$video1' allowfullscreen></iframe>
                <iframe src='$video2' allowfullscreen></iframe>
            </div>
        </main>
        <footer>
            <p>📍 Endereço: Rua Tiradentes 251, Barra, Muriaé - MG</p>
        </footer>
    </body>
    </html>
    ";
    file_put_contents($arquivoPc, $pagina);

    // Atualizar index.html com novo card
    $novoCard = "
    <div class='pc-card'>
      <div class='admin-opcoes' style='display:none;'>
        <button onclick='excluirPc(this)'>⋮</button>
      </div>
      <img src='$imagem' alt='$titulo'>
      <h2>$titulo</h2>
      <a href='$arquivoPc' class='btn'>Ver detalhes</a>
      <a href='https://wa.me/5532988716800' target='_blank' class='btn-whatsapp'>📱 WhatsApp</a>
    </div>
    ";
    $index = file_get_contents("index.html");
    $index = str_replace("</main>", $novoCard . "\n</main>", $index);
    file_put_contents("index.html", $index);

    echo "<script>alert('✅ PC cadastrado com sucesso!'); window.location.href='index.html';</script>";
}
?>
