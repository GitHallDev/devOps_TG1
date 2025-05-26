<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // adapte le chemin si nécessaire

class MailService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function envoyerNotification(string $type, int|string $id, string $statut) {
        $email = '';
        $sujet = '';
        $message = '';
        $nom='';
        $prenom='';
        
        switch ($type) {
            case 'candidatures':
                $stmt = $this->pdo->prepare("SELECT u.email, u.nom, u.prenom, CONCAT('Votre candidature pour stage : ', p.sujet) AS sujet
                                             FROM t1_candidatures c
                                             JOIN t1_users u ON c.ID_user = u.ID_user
                                             JOIN t1_propositions p ON c.ID_prop = p.ID_prop
                                             WHERE c.ID_cand = ?");
                break;

            case 'propositions':
                $stmt = $this->pdo->prepare("SELECT u.email, u.nom, u.prenom, CONCAT(' Votre proposition de stage : ', p.sujet) AS sujet
                                             FROM t1_propositions p
                                             JOIN t1_users u ON p.ID_user = u.ID_user
                                             WHERE p.ID_prop = ?");
            case 'planifications':
                $stmt = $this->pdo->prepare("SELECT u.email, u.nom, u.prenom, CONCAT('Votre Soutenance : Pour le ', s.Date) AS sujet
                                             FROM t1_soutenances s
                                             JOIN t1_stages st ON s.ID_stage = st.ID_stage
                                             JOIN t1_users u ON st.ID_user = u.ID_user
                                             WHERE s.ID_sout =?");
                break;

            case 'soutenances':
                $stmt = $this->pdo->prepare("SELECT u.email, u.nom, u.prenom, 'Notification de soutenance' AS sujet
                                             FROM t1_soutenances s
                                             JOIN t1_stages st ON s.ID_stage = st.ID_stage
                                             JOIN t1_users u ON st.ID_user = u.ID_user
                                             WHERE s.ID_sout = ?");
                break;

            default:
                return false;
        }

        $stmt->execute([$id]);
        $result = $stmt->fetch();
        // echo "Résultat de la requête : ";
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";
        if (!$result) return false;

        $email = $result['email'];
        $sujet = $result['sujet'];
        $nom = $result['nom'];
        $prenom = $result['prenom'];
        $etat = ($statut === 'valide' || $statut === 'accepter') ? 'acceptée' : (($statut === 'planifiée' || $statut === 'programmée')? 'programmer':'refusée');
        $message = "Bonjour, $prenom $nom \n\nVotre $type a été $etat.\n\nCordialement,\nL'équipe de gestion d' eDoniyan (IA4D).";


        return $this->envoyerMail($email, $sujet, $message);
    }

    private function envoyerMail($to, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP (exemple avec Gmail)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'doumbiab970@gmail.com'; // ⚠️ À remplacer par ton email
            $mail->Password   = 'hqjzgaluvrpvufyc'; // ⚠️ Mot de passe d'application
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Paramètres du mail
            $mail->setFrom('doumbiab970@gmail.com', 'Equipe de Gestion d\'eDoniyan (IA4D) :');
            $mail->addAddress($to); // Adresse du destinataire
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error message
            error_log("Erreur d'envoi de mail : {$mail->ErrorInfo}");
            return false;
        }
    }
}
