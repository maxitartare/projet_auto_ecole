<?php

if (!isset($_SESSION['mail'])) {
	header('location:main.php?rubrique=index');
}


if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = 'choisir_eleve';
}


require_once('modeles/m_achat.php');
require_once('inc/fonctions_diverses.php');


switch ($action) {
	case 'choisir_eleve':
		if (isset($_POST['soumission'])) {
			header('location:main.php?rubrique=achat&action=lister_achats_eleve&id_eleve='.$_POST['eleves']);
		} else {
			$eleves = getEleves();
			require_once('vues/v_achat.choisir_eleve.php');
		}
		break;
	case 'lister_achats_eleve':
		$eleve = getEleve($_GET['id_eleve']);
		$achats = getAchatsTicketsEleve($_GET['id_eleve']);
		require_once('vues/v_achat.lister_achats_eleve.php');
		break;
	case 'supprimer_achat_eleve':
		supprimerAchatTickets($_GET['id_achat']);
		header('location:main.php?rubrique=achat&action=lister_achats_eleve&id_eleve='.$_GET['id_eleve']);
		break;
	case 'ajouter_achat_eleve':
		$eleve = getEleve($_GET['id_eleve']);
		$date = '';
		$quantite = '';
		$moyenPaiement = '';
		
		if (isset($_POST['soumission'])) {
			$date = $_POST['date'];
			$quantite = $_POST['quantite'];
			$moyenPaiement = $_POST['moyen_paiement'];
			
			if ($date == '' or $quantite == '') {
				$message = 'Tous les champs doivent être renseignés.';
			} else {
				if (validateDate($date) && is_numeric($quantite)) {
					creerAchatTickets($date, $quantite, $moyenPaiement, $_GET['id_eleve']);
					header('location:main.php?rubrique=achat&action=lister_achats_eleve&id_eleve='.$_GET['id_eleve']);	
				} else {
					if (!validateDate($date)) {
						$message = 'La valeur saisie dans le champ "Date" est incorrecte. ';
					}
					
					if (!is_numeric($quantite)) {
						$message .= 'La valeur saisie dans le champ "Quantité" est incorrecte. ';
					}
				}
			}
		}
		
		require_once('vues/v_achat.ajouter_achat_eleve.php');
		break;
	case 'editer_achat_eleve':
		$date = '';
		$quantite = '';
		$moyenPaiement = '';
		
		if (isset($_POST['soumission'])) {
			$date = $_POST['date'];
			$quantite = $_POST['quantite'];
			$moyenPaiement = $_POST['moyen_paiement'];
			
			if ($date == '' or $quantite == '') {
				$message = 'Tous les champs doivent être renseignés.';
			} else {
				if (validateDate($date) && is_numeric($quantite)) {
					modifierAchatTickets($date, $quantite, $moyenPaiement, $_GET['id_achat']);
					header('location:main.php?rubrique=achat&action=lister_achats_eleve&id_eleve='.$_GET['id_eleve']);
				} else {
					if (!validateDate($date)) {
						$message = 'La valeur saisie dans le champ "Date" est incorrecte. ';
					}
					
					if (!is_numeric($quantite)) {
						$message .= 'La valeur saisie dans le champ "Quantité" est incorrecte. ';
					}
				}
			}

		} else {
			$achatTickets = getAchatTicket($_GET['id_achat']);
			
			$date = $achatTickets['DATEACHAT'];
			$quantite = $achatTickets['QUANTITE'];
			$moyenPaiement = $achatTickets['MOYENPAIEMENT'];
		}
		
		require_once('vues/v_achat.editer_achat_eleve.php');
		break;	
}


?>