<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    // Core modules
    case Order = 'new-order';
    case Offer = 'new-offer';
    case Message = 'message';
    case SmsOtp = 'sms-otp';

        // Community modules
    case Comments = 'comments';
    case Community = 'community';
    case NewReview = 'new-review';
    case NewLike = 'new-like';

        // Support modules  
    case UserTicket = 'user-ticket';
    case SupportMessage = 'support-message';

        // System modules
    case General = 'general';
    case PushNotifications = 'push-notifications';

        // Product modules
    case NewProduct = 'new-product';
    case NewProcedure = 'new-procedure';

        // Order updates
    case UpdateOrder = 'update-order';
    case UpdateConsultation = 'update-consultation';
}
