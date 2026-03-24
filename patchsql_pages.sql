-- ============================================
-- UPDATE TERMS & CONDITIONS (tbl_policy id=1)
-- ============================================
UPDATE tbl_policy SET
head = 'Terms and Conditions',
content = '<h4>Welcome to Ajwyn</h4>
<p>These terms and conditions outline the rules and regulations for the use of Ajwyn''s website and services located at www.ajwyn.site.</p>

<h5>1. Acceptance of Terms</h5>
<p>By accessing this website, you accept these terms and conditions in full. Do not continue to use Ajwyn if you do not accept all of the terms and conditions stated on this page.</p>

<h5>2. Account Registration</h5>
<p>To place an order, you may need to register an account. You are responsible for maintaining the confidentiality of your account information and for all activities that occur under your account. You must provide accurate and complete information during the registration process.</p>

<h5>3. Products and Pricing</h5>
<p>All products listed on our website are subject to availability. We reserve the right to modify prices at any time without prior notice. Prices displayed are in Indian Rupees (INR) and include applicable taxes unless stated otherwise.</p>

<h5>4. Orders and Payments</h5>
<p>Once an order is placed, you will receive a confirmation via WhatsApp, SMS, or email. Payment must be completed through our secure payment gateway. We accept various payment methods including UPI, debit/credit cards, and net banking.</p>

<h5>5. Shipping and Delivery</h5>
<p>We aim to deliver products within the estimated delivery time. Delivery times may vary based on your location and product availability. Shipping charges, if applicable, will be displayed at checkout before payment.</p>

<h5>6. User Conduct</h5>
<p>You agree not to use the website for any unlawful purpose, to impersonate any person or entity, or to interfere with the proper working of the website.</p>

<h5>7. Intellectual Property</h5>
<p>All content on this website including text, images, logos, and graphics are the property of Ajwyn and are protected by intellectual property laws. You may not reproduce, distribute, or use any content without prior written permission.</p>

<h5>8. Limitation of Liability</h5>
<p>Ajwyn shall not be liable for any indirect, incidental, or consequential damages arising from the use of our website or products purchased from us.</p>

<h5>9. Changes to Terms</h5>
<p>We reserve the right to update these terms at any time. Changes will be posted on this page and your continued use of the website constitutes acceptance of the updated terms.</p>

<h5>10. Contact Us</h5>
<p>If you have any questions about these Terms and Conditions, please contact us at <a href="mailto:info@ajwyn.site">info@ajwyn.site</a>.</p>'
WHERE id = 1;

-- ============================================
-- UPDATE CANCELLATION & REFUND POLICY (tbl_policy id=2)
-- ============================================
UPDATE tbl_policy SET
head = 'Cancellation and Refund Policy',
content = '<h4>Cancellation and Refund Policy</h4>
<p>At Ajwyn, we strive to provide the best shopping experience. Please read our cancellation and refund policy carefully.</p>

<h5>1. Order Cancellation</h5>
<p>You may cancel your order before it has been shipped. To cancel an order, go to "My Orders" in your account and click the cancel button. Once an order has been shipped, it cannot be cancelled.</p>

<h5>2. Refund Eligibility</h5>
<p>Refunds are applicable in the following cases:</p>
<ul>
<li>The product received is damaged or defective</li>
<li>The wrong product was delivered</li>
<li>The order was cancelled before shipping</li>
<li>The product does not match the description on the website</li>
</ul>

<h5>3. Refund Process</h5>
<p>To request a refund, please contact our support team within 7 days of receiving the product. You may be required to provide photos of the damaged/defective product. Once your refund request is approved, the amount will be credited back to your original payment method within 5-7 business days.</p>

<h5>4. Return Conditions</h5>
<p>Products must be returned in their original packaging and unused condition. Certain products such as perishable goods, personal care items, and customized products are not eligible for return.</p>

<h5>5. Shipping Charges for Returns</h5>
<p>If the return is due to a defect or wrong product, Ajwyn will bear the return shipping cost. For other returns, the customer may need to bear the return shipping charges.</p>

<h5>6. Non-Refundable Items</h5>
<p>Gift cards, downloadable products, and items marked as non-returnable are not eligible for refunds.</p>

<h5>7. Contact Us</h5>
<p>For any cancellation or refund queries, please reach out to us at <a href="mailto:info@ajwyn.site">info@ajwyn.site</a>.</p>'
WHERE id = 2;

-- ============================================
-- UPDATE PRIVACY POLICY (tbl_policy id=3)
-- ============================================
UPDATE tbl_policy SET
head = 'Privacy Policy',
content = '<h4>Privacy Policy</h4>
<p>Ajwyn ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, and safeguard your personal information when you visit our website www.ajwyn.site.</p>

<h5>1. Information We Collect</h5>
<p>We collect the following types of information:</p>
<ul>
<li><strong>Personal Information:</strong> Name, email address, phone number, and shipping address when you create an account or place an order.</li>
<li><strong>Payment Information:</strong> Payment details are processed securely through our payment gateway. We do not store your card details on our servers.</li>
<li><strong>Usage Information:</strong> We may collect information about how you use our website, including pages visited, time spent, and browser type.</li>
</ul>

<h5>2. How We Use Your Information</h5>
<p>We use your information to:</p>
<ul>
<li>Process and deliver your orders</li>
<li>Send order confirmations and updates via WhatsApp, SMS, and email</li>
<li>Improve our website and services</li>
<li>Respond to your inquiries and provide customer support</li>
<li>Send promotional communications (with your consent)</li>
</ul>

<h5>3. Information Sharing</h5>
<p>We do not sell, trade, or rent your personal information to third parties. We may share your information with trusted service providers who assist us in operating our website, processing payments, and delivering products, subject to confidentiality agreements.</p>

<h5>4. Cookies</h5>
<p>Our website uses cookies to enhance your browsing experience. Cookies help us understand how you use our site and allow us to improve our services. You can choose to disable cookies through your browser settings.</p>

<h5>5. Data Security</h5>
<p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.</p>

<h5>6. Third-Party Links</h5>
<p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of these external sites.</p>

<h5>7. Your Rights</h5>
<p>You have the right to access, update, or delete your personal information. You may also opt out of receiving promotional communications at any time by contacting us.</p>

<h5>8. Changes to This Policy</h5>
<p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated effective date.</p>

<h5>9. Contact Us</h5>
<p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:info@ajwyn.site">info@ajwyn.site</a>.</p>'
WHERE id = 3;

-- ============================================
-- UPDATE FAQ in tbl_footermenus
-- Find the FAQ entry and update its content
-- ============================================
UPDATE tbl_footermenus SET
content = '<h4>Frequently Asked Questions</h4>

<h5>1. How do I place an order?</h5>
<p>Browse our products, add items to your cart, and proceed to checkout. You can log in using your mobile number and OTP. Select your delivery address, choose a payment method, and confirm your order.</p>

<h5>2. What payment methods do you accept?</h5>
<p>We accept UPI, debit cards, credit cards, net banking, and other payment methods through our secure payment gateway.</p>

<h5>3. How can I track my order?</h5>
<p>Once your order is shipped, you will receive a tracking ID via WhatsApp/SMS. You can also check your order status by logging into your account and visiting the "My Orders" section.</p>

<h5>4. What is the delivery time?</h5>
<p>Delivery typically takes 3-7 business days depending on your location. You will see the estimated delivery time at checkout.</p>

<h5>5. Can I cancel my order?</h5>
<p>Yes, you can cancel your order before it has been shipped. Go to "My Orders" and click the cancel button. Once shipped, cancellation is not possible.</p>

<h5>6. How do I return a product?</h5>
<p>If you received a damaged or wrong product, contact us within 7 days of delivery at <a href="mailto:info@ajwyn.site">info@ajwyn.site</a>. We will arrange for a return and refund.</p>

<h5>7. When will I receive my refund?</h5>
<p>Once your return is approved, the refund will be processed within 5-7 business days to your original payment method.</p>

<h5>8. How do I create an account?</h5>
<p>Click the "Sign Up" button, enter your mobile number, and verify with the OTP sent to your phone. Your account will be created automatically.</p>

<h5>9. I did not receive the OTP. What should I do?</h5>
<p>Please wait a few moments as OTPs may take up to a minute to arrive. Check your WhatsApp messages and SMS inbox. If you still haven''t received it, try again after 5 minutes. Make sure your mobile number is correct.</p>

<h5>10. How do I contact customer support?</h5>
<p>You can reach us via email at <a href="mailto:info@ajwyn.site">info@ajwyn.site</a> or through WhatsApp. Our contact details are available in the footer of our website.</p>

<h5>11. Is my payment information secure?</h5>
<p>Yes, all payments are processed through a secure, encrypted payment gateway. We do not store your card or bank details on our servers.</p>

<h5>12. Do you deliver to my area?</h5>
<p>We deliver across India. Delivery availability and charges may vary based on your location. Enter your pincode at checkout to check delivery availability.</p>'
WHERE head = 'FAQ' OR head = 'Faq' OR head = 'faq';
