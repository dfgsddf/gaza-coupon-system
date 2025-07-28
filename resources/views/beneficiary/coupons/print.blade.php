<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الكوبون - {{ $coupon->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white !important;
                font-size: 14pt;
            }
            
            .coupon-container {
                border: 3px dashed #007bff !important;
                box-shadow: none !important;
            }
        }
        
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            background: #f8f9fa;
        }
        
        .coupon-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border: 3px dashed #007bff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .coupon-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .coupon-value {
            font-size: 4rem;
            font-weight: bold;
            color: #28a745;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .coupon-code {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            letter-spacing: 3px;
            padding: 10px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            display: inline-block;
        }
        
        .store-name {
            background: #28a745;
            color: white;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .expiry-warning {
            background: #ffc107;
            color: #212529;
            padding: 10px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .instructions {
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .qr-placeholder {
            width: 100px;
            height: 100px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- أزرار التحكم -->
        <div class="text-center mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-print me-2"></i>
                طباعة الكوبون
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">
                <i class="fas fa-times me-2"></i>
                إغلاق
            </button>
        </div>
        
        <!-- الكوبون -->
        <div class="coupon-container">
            <!-- Header -->
            <div class="coupon-header">
                <h1 class="mb-3">
                    <i class="fas fa-ticket-alt me-3"></i>
                    كوبون خصم
                </h1>
                <h3 class="mb-0">نظام قسائم غزة</h3>
            </div>
            
            <!-- قيمة الكوبون -->
            <div class="text-center py-4">
                <div class="coupon-value">
                    {{ number_format($coupon->value, 0) }}
                    <small style="font-size: 2rem;">شيكل</small>
                </div>
                <p class="text-muted fs-5">قيمة الكوبون</p>
            </div>
            
            <!-- اسم المتجر -->
            <div class="store-name text-center">
                <i class="fas fa-store me-2"></i>
                {{ $coupon->store_name ?? 'متجر عام' }}
            </div>
            
            <!-- معلومات الكوبون -->
            <div class="p-4">
                <div class="row">
                    <div class="col-md-8">
                        <!-- كود الكوبون -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold">كود الكوبون:</label>
                            <div class="coupon-code">
                                {{ $coupon->code }}
                            </div>
                        </div>
                        
                        <!-- تاريخ انتهاء الصلاحية -->
                        <div class="expiry-warning text-center">
                            <i class="fas fa-calendar-times me-2"></i>
                            <strong>ينتهي في:</strong>
                            {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y') }}
                        </div>
                        
                        <!-- الوصف -->
                        @if($coupon->description)
                            <div class="mb-3">
                                <strong>الوصف:</strong>
                                <p class="mb-0">{{ $coupon->description }}</p>
                            </div>
                        @endif
                        
                        <!-- تاريخ الإصدار -->
                        <div class="mb-3">
                            <strong>تاريخ الإصدار:</strong>
                            {{ \Carbon\Carbon::parse($coupon->created_at)->format('d/m/Y') }}
                        </div>
                    </div>
                    
                    <!-- مكان للـ QR Code في المستقبل -->
                    <div class="col-md-4 text-center">
                        <div class="qr-placeholder mx-auto">
                            <i class="fas fa-qrcode fa-2x text-muted"></i>
                        </div>
                        <small class="text-muted d-block mt-2">رمز الاستجابة السريعة</small>
                    </div>
                </div>
                
                <!-- تعليمات الاستخدام -->
                <div class="instructions">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        تعليمات الاستخدام:
                    </h6>
                    <ol class="mb-0">
                        <li>اعرض هذا الكوبون المطبوع في المتجر المحدد</li>
                        <li>تأكد من أن تاريخ الصلاحية لم ينته</li>
                        <li>يجب استخدام الكوبون مرة واحدة فقط</li>
                        <li>لا يمكن استبدال الكوبون بنقد</li>
                    </ol>
                </div>
                
                <!-- معلومات المستفيد -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">
                                <strong>المستفيد:</strong><br>
                                {{ auth()->user()->name }}
                            </small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">
                                <strong>رقم الكوبون:</strong><br>
                                #{{ $coupon->id }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-center py-3 bg-light border-top">
                <small class="text-muted">
                    نظام قسائم غزة - {{ date('Y') }} © جميع الحقوق محفوظة
                </small>
            </div>
        </div>
        
        <!-- ملاحظات إضافية -->
        <div class="text-center mt-4 no-print">
            <div class="alert alert-info">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>نصيحة:</strong> احتفظ بنسخة من هذا الكوبون واعرضه في المتجر المحدد قبل انتهاء صلاحيته
            </div>
        </div>
    </div>
    
    <script>
        // طباعة تلقائية عند فتح الصفحة (اختياري)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html> 