<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SmsTemplate;




class SmsTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::all();
        return response()->json([
            "message" => "SMS Templates retrieved successfully",
            "data" => $templates
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $template = SmsTemplate::create($validated);

        return response()->json([
            "message" => "SMS Template created successfully",
            "data" => $template
        ], 201);
    }

    public function show($id)
    {
        $template = SmsTemplate::find($id);
        if (!$template) {
            return response()->json(["message" => "SMS Template not found"], 404);
        }
        return response()->json([
            "message" => "SMS Template retrieved successfully",
            "data" => $template
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = SmsTemplate::find($id);
        if (!$template) {
            return response()->json(["message" => "SMS Template not found"], 404);
        }
        
        $template->update($request->all());
        
        return response()->json([
            "message" => "SMS Template updated successfully",
            "data" => $template
        ]);
    }

    public function destroy($id)
    {
        $template = SmsTemplate::find($id);
        if (!$template) {
            return response()->json(["message" => "SMS Template not found"], 404);
        }
        
        $template->delete();
        
        return response()->json([
            "message" => "SMS Template deleted successfully"
        ]);
    }
    public function sendOtp(Request $request)
{
    $mobile = $request->mobile;
    $otp = rand(100000,999999);

    $response = Http::withHeaders([
        'authorization' => 'YOUR_FAST2SMS_API_KEY',
    ])->post('https://www.fast2sms.com/dev/bulkV2', [
        "variables_values" => $otp,
        "route" => "otp",
        "numbers" => $mobile,
    ]);

    return response()->json([
        'message' => 'OTP sent successfully',
        'otp' => $otp
    ]);
}
}
