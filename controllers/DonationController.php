<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Csrf;
use Core\Mailer;
use Core\Session;
use Models\Donation;
use Models\User;

final class DonationController extends Controller
{
    public function __construct(array $config, private Donation $donations, private User $users)
    {
        parent::__construct($config);
    }

    public function submit(): void
    {
        $auth = Session::user();
        if (!$auth || $auth['role'] !== 'user') {
            $this->redirect('/login');
        }

        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            $this->redirect('/dashboard');
        }

        $description = trim($_POST['description'] ?? '');
        $quantity = (int) ($_POST['quantity'] ?? 0);
        $pickupAddress = trim($_POST['pickup_address'] ?? '');

        if ($description === '' || $pickupAddress === '' || $quantity < 1) {
            Session::set('flash_error', 'Please fill all donation fields correctly.');
            $this->redirect('/dashboard');
        }

        $donationId = $this->donations->create((int) $auth['id'], $description, $quantity, $pickupAddress);

        $mailBody = "Donation ID: {$donationId}\nDonor: {$auth['name']}\nEmail: {$auth['email']}\nDescription: {$description}\nQuantity: {$quantity}\nPickup Address: {$pickupAddress}";
        Mailer::send($this->config['mail']['admin_email'], 'New Dress Donation Submitted', $mailBody, $this->config['mail']);
        Mailer::send($auth['email'], 'Donation Received - NGO Platform', $mailBody, $this->config['mail']);

        Session::set('flash_success', 'Donation submitted successfully. Notification email sent.');
        $this->redirect('/dashboard');
    }

    public function adminAssignApprove(): void
    {
        $auth = Session::user();
        if (!$auth || $auth['role'] !== 'admin') {
            $this->redirect('/login');
        }

        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            $this->redirect('/dashboard');
        }

        $donationId = (int) ($_POST['donation_id'] ?? 0);
        $orphanId = (int) ($_POST['orphanage_user_id'] ?? 0);

        if ($donationId < 1 || $orphanId < 1) {
            Session::set('flash_error', 'Invalid assignment request.');
            $this->redirect('/dashboard');
        }

        $this->donations->assignAndApprove($donationId, $orphanId);
        Session::set('flash_success', 'Donation approved and assigned to orphanage.');
        $this->redirect('/dashboard');
    }

    public function orphanageDecision(): void
    {
        $auth = Session::user();
        if (!$auth || $auth['role'] !== 'orphanage') {
            $this->redirect('/login');
        }

        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::set('flash_error', 'Invalid CSRF token.');
            $this->redirect('/dashboard');
        }

        $donationId = (int) ($_POST['donation_id'] ?? 0);
        $decision = (string) ($_POST['decision'] ?? '');

        if (!in_array($decision, ['accepted', 'rejected'], true) || $donationId < 1) {
            Session::set('flash_error', 'Invalid decision.');
            $this->redirect('/dashboard');
        }

        $this->donations->orphanageDecision($donationId, (int) $auth['id'], $decision);
        Session::set('flash_success', 'Donation status updated.');
        $this->redirect('/dashboard');
    }
}
