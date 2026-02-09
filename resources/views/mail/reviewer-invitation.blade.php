<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-family: 'Segoe UI', Arial, sans-serif; background-color:#f8fafc; padding:24px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#ffffff; border-radius:16px; overflow:hidden;">
                <tr>
                    <td style="padding: 32px 32px 16px 32px;">
                        <h1 style="margin:0; font-size:20px; color:#1e293b; font-weight:700;">
                            {{ __('Invitation to Review') }}
                        </h1>
                        <p style="margin:12px 0 0 0; font-size:14px; color:#475569;">
                            {{ __('Dear :name,', ['name' => $reviewer->name]) }}<br>
                            {{ __('You are invited to review the following manuscript for the Arab Journal for Science and Research Publishing.') }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px 24px 32px;">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f1f5f9; border-radius:12px; padding:20px;">
                            <tr>
                                <td style="font-size:13px; color:#334155;">
                                    <strong>{{ __('Manuscript Title') }}:</strong><br>
                                    {{ $submission->article_title ?? __('N/A') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:12px; font-size:13px; color:#334155;">
                                    <strong>{{ __('Journal') }}:</strong><br>
                                    {{ optional($submission->journal)->title ?? __('Not specified') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:12px; font-size:13px; color:#334155;">
                                    <strong>{{ __('Abstract') }}:</strong><br>
                                    <span style="white-space:pre-line;">{{ \Illuminate\Support\Str::limit($submission->article_abstract, 600) }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px 24px 32px;">
                        <p style="font-size:14px; color:#1e293b; margin:0 0 12px 0;">
                            {{ __('Please confirm whether you can review this manuscript and declare any conflicts of interest.') }}
                        </p>
                        <table cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td align="center" bgcolor="#2563eb" style="border-radius:999px;">
                                    <a href="{{ $invitationLink }}" style="display:inline-block; padding:14px 28px; color:#fff; text-decoration:none; font-weight:600; font-size:14px;">
                                        {{ __('Respond to Invitation') }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <p style="font-size:12px; color:#64748b; margin:12px 0 0 0;">
                            {{ __('If the button does not work, copy and paste the following link into your browser:') }}<br>
                            <a href="{{ $invitationLink }}" style="color:#2563eb;">{{ $invitationLink }}</a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px 32px 32px; font-size:12px; color:#64748b;">
                        <p style="margin:0 0 8px 0;">
                            {{ __('Thank you for supporting our peer-review process.') }}
                        </p>
                        <p style="margin:0;">
                            {{ __('Editorial Office') }}<br>
                            {{ __('Arab Journal for Science and Research Publishing') }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

