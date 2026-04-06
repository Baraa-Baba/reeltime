@extends('layouts.app')

@section('title', 'Verify Email | ReelTime')

@section('content')
<main class="verify-email-container">
    <div class="verify-email-card">
        <div class="verify-email-icon">
            <i class="fas fa-envelope"></i>
        </div>
        
        <h1>Verify Your Email</h1>
        
        <p class="verify-email-message">
            Thanks for signing up! Before you can start using ReelTime, please verify your email address.
        </p>
        
        <p class="verify-email-email">
            <strong>{{ Auth::user()->email }}</strong>
        </p>
        
        <p class="verify-email-instruction">
            We've sent a verification link to your email address. Click the link in the email to verify your account.
        </p>
        
        <div class="verify-email-actions">
            <p class="resend-email-text">
                Didn't receive the email?
            </p>
            
            <form id="resendForm" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="button button-primary">
                    Resend Verification Email
                </button>
            </form>
        </div>
        
        <div class="verify-email-footer">
            <a href="{{ route('home') }}" class="link">Back to Home</a>
        </div>
    </div>
</main>

<style>
    .verify-email-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 100px);
        padding: 20px;
    }
    
    .verify-email-card {
        background: var(--color-surface-card, #1a1a1a);
        border-radius: 12px;
        padding: 60px 40px;
        max-width: 500px;
        width: 100%;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    
    .verify-email-icon {
        font-size: 64px;
        color: var(--color-primary, #ff6b6b);
        margin-bottom: 20px;
    }
    
    .verify-email-card h1 {
        font-size: 28px;
        margin-bottom: 20px;
        color: var(--color-text-primary, #ffffff);
    }
    
    .verify-email-message {
        font-size: 16px;
        color: var(--color-text-secondary, #999999);
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .verify-email-email {
        font-size: 16px;
        color: var(--color-primary, #ff6b6b);
        font-weight: 600;
        margin-bottom: 20px;
        word-break: break-all;
    }
    
    .verify-email-instruction {
        font-size: 14px;
        color: var(--color-text-secondary, #999999);
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .verify-email-actions {
        margin-bottom: 30px;
    }
    
    .resend-email-text {
        font-size: 14px;
        color: var(--color-text-secondary, #999999);
        margin-bottom: 15px;
    }
    
    #resendForm {
        margin-bottom: 20px;
    }
    
    .verify-email-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
    }
    
    .verify-email-footer a {
        color: var(--color-primary, #ff6b6b);
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }
    
    .verify-email-footer a:hover {
        color: var(--color-primary-light, #ff8787);
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .verify-email-card {
            padding: 40px 20px;
        }
        
        .verify-email-icon {
            font-size: 48px;
        }
        
        .verify-email-card h1 {
            font-size: 22px;
        }
    }
</style>

<script>
    document.getElementById('resendForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        try {
            const response = await fetch('{{ route("verification.send") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Verification email sent! Please check your inbox.');
            } else {
                alert('Error: ' + (data.message || 'Failed to resend verification email'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        }
    });
</script>
@endsection
