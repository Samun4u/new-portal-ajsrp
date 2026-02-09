<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTempSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['user_id' => 1,'tenant_id' => null,'category' => 'email-verify', 'title' => 'Email Verification', 'slug' => 'email-verification', 'subject' => 'Verify Your Account', 'body' => '<p>Hello, {{username}}
            </p><p>            Thank you for creating an account with us. We\'re excited to have you as a part of our community!

                Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:
            </p><p>

                Link: {{email_verify_url}}
                        </p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1,'tenant_id' => null,'category' => 'reset-password','title' => 'Password Reset', 'slug' => 'password-reset', 'subject' => 'Reset your password', 'body' => '<div><b>Hello</b> ,{{username}}</div><div><br></div><div>we\'re sending you this email because you requested a password reset. Click on this link to create a new password.</div><div><br></div><div>Set a new password . Here is a link -</div><div><br></div><div>Link :&nbsp;<span style="background-color: rgb(209, 231, 221); color: rgb(15, 81, 50); font-family: inter, sans-serif; text-align: var(--bs-body-text-align);">{{<b>reset_password_url</b>}}</span></div><div><br></div><div>If you didn\'t request a password reset, you can ignore this email. Your password will not be a changed.</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket','title' => 'Ticket Create Notify For Client', 'slug' => 'ticket-create-notify-for-client', 'subject' => 'New Ticket Created - {{tracking_no}}', 'body' =>'<p><b>Dear</b> {{username}},
            </p><p>
                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:
            </p><p>
                            Tracking No: <b>{{tracking_no}}
            </p><p>                Date Created: {{ticket_created_time}}
            </p><p>                Title: {{ticket_title}}</p><p>
                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.

                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.

                            Thank you for using our services!
            </p><p><b>
                Best regards</b>,
                            {{app_name}}</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket','title' => 'Ticket Create Notify For Admin', 'slug' => 'ticket-create-notify-for-admin', 'subject' => 'New Ticket Created - {{tracking_no}}', 'body' => '<p><b>Dear</b> {{username}},
            </p><p>
                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:
            </p><p>
                            Tracking No: <b>{{tracking_no}}
            </p><p>                Date Created: {{ticket_created_time}}
            </p><p>                Title: {{ticket_title}}</p><p>
                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.

                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.

                            Thank you for using our services!
            </p><p><b>
                Best regards</b>,
                            {{app_name}}</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket', 'title' => 'Ticket Create Notify For Team Member', 'slug' => 'ticket-create-notify-for-team-member', 'subject' => 'New Ticket Created - {{tracking_no}}', 'body' => '<p><b>Dear</b> {{username}},
            </p><p>
                            We are happy to inform you that a new ticket has been successfully created in our system with the following details:
            </p><p>
                            Tracking No: <b>{{tracking_no}}
            </p><p>                Date Created: {{ticket_created_time}}
            </p><p>                Title: {{ticket_title}}</p><p>
                            You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.

                            If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.

                            Thank you for using our services!
            </p><p><b>
                Best regards</b>,
                            {{app_name}}</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket','title' => 'Ticket Conversation For Admin', 'slug' => 'ticket-conversation-for-admin', 'subject' => 'New Reply For Your Ticket -{{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>A new ticket has been created in our system. Ticket Tracking No: {{tracking_no}} with the following details:</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style="font-weight: bolder;">Best regards,</span>&nbsp;{{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket','title' => 'Ticket Conversation For Team Member', 'slug' => 'ticket-conversation-for-team-member', 'subject' => 'New Reply For Your Ticket - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket', 'title' => 'Ticket Conversation For Client', 'slug' => 'ticket-conversation-for-client', 'subject' => 'New Reply For Your Ticket - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket', 'title' => 'Ticket Status Change For Client', 'slug' => 'ticket-status-change-for-client', 'subject' => 'Ticket Status Changed - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has ticket status change in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'ticket','title' => 'Ticket assign For Team Member', 'slug' => 'ticket-assign-for-team-member', 'subject' => 'ticket assign', 'body' => 'ticket asaingn', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'quotation','title' => 'Quotation Email Send', 'slug' => 'quotation-email-send', 'subject' => 'ticket assign', 'body' => 'ticket asaingn', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'invoice','title' => 'Invoice Unpaid Notify For Client', 'slug' => 'invoice-unpaid-notify-for-client', 'subject' => 'Invoice Unpaid Notify For Client', 'body' => 'Invoice Unpaid Notify For Client', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => '2','tenant_id' => 'zainiklab','category' => 'invoice','title' => 'Invoice Paid Notify For Client', 'slug' => 'invoice-paid-notify-for-client', 'subject' => 'Invoice Paid Notify For Client', 'body' => 'Invoice Paid Notify For Client', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        EmailTemplate::insert($data);
    }
}
