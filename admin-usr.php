<?php
$servername = "localhost";
$username = "lotfi";
$password = "";
$dbname = "users";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    $table = $_POST['delete_table'];
    
    $sql = "DELETE FROM $table WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Enregistrement supprimé avec succès.');</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'enregistrement: " . $conn->error . "');</script>";
    }
}

$sqlUsers = "SELECT id, nom, prenom, email, phone, role FROM users";
$resultUsers = $conn->query($sqlUsers);

$sqlPublications = "SELECT id, users_id, titre, type_bien, nb_pieces, prix, caution, localisation, description, meuble, ascenseur, photo_localisation, photo1, photo2, photo3, photo4, photo5, photo6 FROM annonces";
$resultPublications = $conn->query($sqlPublications);
?>

<!DOCTYPE html>
<html>
    
<head>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Gestion des Utilisateurs et Publications</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            background-color: #f4f4f9;
        }
        header {
            background-color: #363636;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 12px;
            text-align: center;
            width: 100%;
            position: fixed;
            top: 0;
            z-index: 1000;
        }
        .grnd-titre {
            margin: -10px;
            color: white;
            font-family: 'Roboto', sans-serif;
            font-size: 40px;
            font-weight: 700;
        }
        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: orangered;
            position: relative;
            top: 16px;
            margin-left: -12px;
        }
        .sidebar {
            background-color: orangered;
            width: 6cm;
            height: 100vh;
            position: fixed;
            top: 60px;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 0px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar h2 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .sidebar button {
            background-color: transparent;
            color: white;
            border: none;
            text-align: left;
            padding: 15px;
            width: 100%;
            font-size: 18px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar button:hover {
            background-color: #B34700;
            color: white;
        }
        .sidebar button.active {
            background-color: white;
            color: #D35400;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .content {
            margin-left: 6cm;
            padding: 20px;
            margin-top: 60px;
            width: calc(100% - 6cm);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 500;
        }
        td {
            background-color: #ffffff;
        }
        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        .delete-btn {
            color: white;
            background-color: red;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn1{
        position: relative;
        left: 88%;
        color: white;
        background-color: red;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 10px;
        margin: 5px;
        text-align: center;
        font-size: 18px;
        transition: background-color 0.3s, color 0.3s;
        }

     .delete-btn1:hover {
        background-color: black;
        }
        .delete-btn:hover {
        background-color: black;
        }
        h2 {
            font-family: 'Roboto', sans-serif;
            color: orangered;
            
            padding-top: 20px;
            font-weight: 500;
        }
        span {
            color: white;
        }
        .publication-img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin: 5px;
        }
        .photo-grid {
            display: flex;
            flex-wrap: wrap;
        }
    .deconnexion {
     border-radius: 28px;
     position: relative;
     top:43%;
     height: 40px;
     background: #ddd;
     color: rgb(34, 74, 202);
     text-decoration: none;
     display: flex;
     align-items: center;
     margin:6px;
    }
  
  .deconnexion i {
    color: orangered;
    height: 50px;
    min-width: 50px;
    border-radius: 12px;
    line-height: 50px;
    text-align: center;
    font-size: 25px;
    margin-right: 10px;
    
  }
  .linkname{
    color: orangered;
    font-family: 'Roboto', sans-serif;
    font-weight: 600;
    font-size: 19px;
  }
    </style>
    <script>
        function confirmDeletion(id, table) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement ?")) {
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_table').value = table;
                document.getElementById('deleteForm').submit();
            }
        }

        function showSection(section) {
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(sec) {
                sec.style.display = 'none';
            });
            document.getElementById(section).style.display = 'block';

            var buttons = document.querySelectorAll('.sidebar button');
            buttons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.querySelector('.sidebar button[data-section="' + section + '"]').classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            showSection('gestion-utilisateurs');
        });
    </script>
</head>
    <script>
        function confirmDeletion(id, table) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement ?")) {
                document.getElementById('delete_id').value = id;
                document.getElementById('delete_table').value = table;
                document.getElementById('deleteForm').submit();
            }
        }

        function showSection(section) {
            var sections = document.querySelectorAll('.section');
            sections.forEach(function(sec) {
                sec.style.display = 'none';
            });
            document.getElementById(section).style.display = 'block';

            var buttons = document.querySelectorAll('.sidebar button');
            buttons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            document.querySelector('.sidebar button[data-section="' + section + '"]').classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            showSection('gestion-utilisateurs');
        });
    </script>
</head>
<body>

<header>
    <h1 class="grnd-titre">Dream <span>Nest</span></h1>
    <div class="progress-bar"></div>
</header>

<div class="sidebar">
    <h2>Admin</h2>
    <button data-section="gestion-utilisateurs" onclick="showSection('gestion-utilisateurs')">Gestion Utilisateurs</button>
    <button data-section="gestion-publications" onclick="showSection('gestion-publications')">Gestion Publications</button>
    <button data-section="gestion-publicites" onclick="showSection('gestion-publicites')">Gestion Publicités</button>
    <a href="../html/conecter.php" class="deconnexion">
     <i class='bx bx-log-out-circle'></i>
     <span class="linkname">Deconnexion</span>
    </a>
</div>

<div class="content">
    <div id="gestion-utilisateurs" class="section">
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Numéro</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            if ($resultUsers->num_rows > 0) {
                while($row = $resultUsers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["nom"] . "</td>";
                    echo "<td>" . $row["prenom"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["role"] . "</td>";
                    echo "<td><button class='delete-btn' onclick='confirmDeletion(" . $row["id"] . ", \"users\")'>Supprimer</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Aucun utilisateur trouvé</td></tr>";
            }
            ?>
        </table>
    </div>

    <div id="gestion-publications" class="section" style="display:none;">
    <h2>Gestion des Publications:</h2>
    <?php
    if ($resultPublications->num_rows > 0) {
        while($row = $resultPublications->fetch_assoc()) {
            echo "<div class='publication'>";
            echo "<div class='publication-title'>Publication (" . $row["id"] . "):</div>";

            // Affichage des photos
            echo "<table class='photo-table'>";
            $photo_fields = ['photo_localisation', 'photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6'];
            $photo_count = 0;
            echo "<tr>";
            foreach ($photo_fields as $field) {
                if (!empty($row[$field])) {
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row[$field]) . "' class='publication-img'/></td>";
                    $photo_count++;
                    if ($photo_count % 4 === 0) {
                        echo "</tr><tr>";
                    }
                }
            }
            // Ajouter des cellules vides pour maintenir la symétrie
            $remaining_photos = $photo_count % 4;
            if ($remaining_photos !== 0) {
                for ($i = 0; $i < (4 - $remaining_photos); $i++) {
                    echo "<td></td>";
                }
            }
            echo "</tr></table>";

            // Affichage des détails de la publication
            echo "<table class='details-table'>";
            echo "<tr><th>Titre:</th><td>" . $row["titre"] . "</td></tr>";
            echo "<tr><th>Type de Bien:</th><td>" . $row["type_bien"] . "</td></tr>";
            echo "<tr><th>Nombre de Pièces:</th><td>" . $row["nb_pieces"] . "</td></tr>";
            echo "<tr><th>Prix:</th><td>" . $row["prix"] . "</td></tr>";
            echo "<tr><th>Caution:</th><td>" . $row["caution"] . "</td></tr>";
            echo "<tr><th>Localisation:</th><td>" . $row["localisation"] . "</td></tr>";
            echo "<tr><th>Description:</th><td>" . $row["description"] . "</td></tr>";
            echo "<tr><th>Meublé:</th><td>" . ($row["meuble"] ? "Oui" : "Non") . "</td></tr>";
            echo "<tr><th>Ascenseur:</th><td>" . ($row["ascenseur"] ? "Oui" : "Non") . "</td></tr>";
            echo "</table>";
            echo "<button class='delete-btn1' onclick='confirmDeletion(" . $row["id"] . ", \"annonces\")'>Supprimer</button>";
            echo "</div>";
        }
    } else {
        echo "<p>Aucune publication trouvée</p>";
    }
    ?>
</div>


    <div id="gestion-publicites" class="section" style="display:none;">
        <h2>Gestion des Publicités:</h2>
        <!-- Contenu pour la gestion des publicités -->
    </div>
</div>

<form id="deleteForm" method="POST">
    <input type="hidden" id="delete_id" name="delete_id" value="">
    <input type="hidden" id="delete_table" name="delete_table" value="">
</form>

</body>
</html>

<?php
$conn->close();
?>
