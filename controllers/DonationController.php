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

        $adminRecipients = array_unique(array_filter([
            $this->config['mail']['admin_email'] ?? '',
            $this->config['mail']['secondary_admin_email'] ?? '',
        ]));

        $adminMessage = $this->adminNewDonationMessage($donationId, $auth, $description, $quantity, $pickupAddress);

        foreach ($adminRecipients as $recipient) {
            Mailer::send((string) $recipient, 'New Donation Submitted', $adminMessage, $this->config['mail']);
        }

        $donorMessage = $this->donorThankYouMessage($donationId, $auth, $description, $quantity, $pickupAddress);
        Mailer::send($auth['email'], 'Thank You for Donating', $donorMessage, $this->config['mail']);

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

        $updated = $this->donations->assignAndApprove($donationId, $orphanId);
        if ($updated < 1) {
            Session::set('flash_error', 'Donation cannot be assigned because it is already finalized.');
            $this->redirect('/dashboard');
        }

        $donation = $this->donations->find($donationId);
        $orphanage = $this->users->findById($orphanId);
        if ($donation && $orphanage && ($orphanage['role'] ?? '') === 'orphanage') {
            $donor = $this->users->findById((int) $donation['user_id']);
            $message = $this->orphanageAssignmentMessage($donationId, $orphanage, $donation, $donor);
            Mailer::send((string) $orphanage['email'], 'Donation Assigned for Your Orphanage', $message, $this->config['mail']);
        }

        Session::set('flash_success', 'Donation assigned to orphanage. Awaiting orphanage decision.');
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

        $updated = $this->donations->orphanageDecision($donationId, (int) $auth['id'], $decision);
        if ($updated < 1) {
            Session::set('flash_error', 'This donation was already finalized and cannot be changed.');
            $this->redirect('/dashboard');
        }

        Session::set('flash_success', 'Donation status updated.');
        $this->redirect('/dashboard');
    }

    private function adminNewDonationMessage(int $donationId, array $donor, string $description, int $quantity, string $pickupAddress): string
    {
        return "Hello Admin,\n\n"
            . "A new dress donation has been submitted.\n\n"
            . "Donation ID: {$donationId}\n"
            . "Donor Name: {$donor['name']}\n"
            . "Donor Email: {$donor['email']}\n"
            . "Description: {$description}\n"
            . "Quantity: {$quantity}\n"
            . "Pickup Address: {$pickupAddress}\n\n"
            . "Please review and assign this donation from the admin dashboard.\n\n"
            . "Regards,\nDress Donation Platform";
    }

    private function donorThankYouMessage(int $donationId, array $donor, string $description, int $quantity, string $pickupAddress): string
    {
        return "Hello {$donor['name']},\n\n"
            . "Thank you for donating to our NGO platform.\n"
            . "Your donation request has been recorded successfully.\n\n"
            . "Donation ID: {$donationId}\n"
            . "Description: {$description}\n"
            . "Quantity: {$quantity}\n"
            . "Pickup Address: {$pickupAddress}\n\n"
            . "Our admin team will review and assign your donation shortly.\n\n"
            . "With gratitude,\nDress Donation Platform";
    }

    private function orphanageAssignmentMessage(int $donationId, array $orphanage, array $donation, ?array $donor): string
    {
        $donorName = $donor['name'] ?? 'Donor';
        $donorEmail = $donor['email'] ?? 'N/A';

        return "Hello {$orphanage['name']},\n\n"
            . "A donation has been assigned to your orphanage.\n\n"
            . "Donation ID: {$donationId}\n"
            . "Donor Name: {$donorName}\n"
            . "Donor Email: {$donorEmail}\n"
            . "Description: {$donation['description']}\n"
            . "Quantity: {$donation['quantity']}\n"
            . "Pickup Address: {$donation['pickup_address']}\n\n"
            . "Please log in to your dashboard and accept or reject this assignment.\n\n"
            . "Regards,\nDress Donation Platform";
    }
}
