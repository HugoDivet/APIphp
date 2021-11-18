<?php
// Se connecter à la base de données
include("db_connect.php");
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            // Récupérer un seul ami
            $id = intval($_GET["id"]);
            getAmi($id);
        } else {
            // Récupérer tous les amis
            getAmis();

        }
        break;
    case 'POST':
        // Ajouter un ami
        addAmi();
        break;
    default:
        // Requête invalide
        header("HTTP/1.0 405 Method Not Allowed");
        break;
    case 'PUT':
        // Modifier un ami
        $id = intval($_GET["id"]);
        updateAmi($id);
        break;
    case 'DELETE':
        // Supprimer un ami
        $idUtilisateur = intval($_GET["idUtilisateur"]);
        $idAmi= intval($_GET["idAmi"]);
        deleteAmi($idUtilisateur,$idAmi);
        break;
}
function getAmis()
{
    global $conn;
    $query = "SELECT * FROM amis";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $response[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function getAmi($id = 0)
{
    global $conn;
    $query = "SELECT * FROM amis";
    if ($id != 0) {
        $query .= " WHERE id=" . $id . " LIMIT 1";
    }
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $response[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}


function addAmi()
{
    global $conn;
    var_dump($_POST);
    if (isset($_POST['idutilisateur'])) {
        $utilisateur1 = filter_input(INPUT_POST, "idutilisateur");
    }
    if (isset($_POST['idami'])) {
        $ami = filter_input(INPUT_POST, "idami");
    }
    $date = date('Y-m-d H:i:s');

    echo $query = "INSERT INTO amis(utilisateur, ami, date) VALUES('" . $utilisateur1 . "', '" . $ami . "', '" . $date . "')";
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Ami ajoute avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'ERREUR!.' . mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}


function updateAmi($id)
{
    global $conn;
    $_PUT = array(); //tableau qui va contenir les données reçues
    parse_str(file_get_contents('php://input'), $_PUT);
    $utilisateur = $_PUT["utilisateur"];
    $ami = $_PUT["ami"];
    $date = date('Y-m-d H:i:s');
    //construire la requête SQL
    $query = "UPDATE amis SET idUtilisateur1='" . $utilisateur . "', idUtilisateur2='" . $ami . "', dateAjout='" . $date . "' WHERE id=" . $id;

    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Ami mis a jour avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => "Echec de la mise a jour d'ami. " . mysqli_error($conn)
        );

    }

    header('Content-Type: application/json');
    echo json_encode($response);
}


function deleteAmi($idUtilisateur, $idAmi)
{
    global $conn;
    $query = "DELETE FROM amis WHERE idUtilisateur=" . $idUtilisateur."AND idAmi=" .$idAmi;
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'Ami supprime avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => "La suppression de l'ami a echoue. " . mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

