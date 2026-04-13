<main>
    <!-- Page Header / Hero Section nhỏ -->
    <section class="page-banner">
        <div class="page-banner-content">
            <h1 class="page-title">Kết Nối Với Zero Watch</h1>
            <p class="page-desc">Chúng tôi luôn sẵn sàng lắng nghe ý kiến của bạn về sản phẩm và dịch vụ.</p>
        </div>
    </section>

    <!-- Content Grid: Thông tin & Form -->
    <section class="section contact-section scroll-reveal is-visible">
        <div class="contact-grid">
            
            <!-- Cột Trái: Thông tin liên hệ & Giới thiệu -->
            <div class="contact-info-col">
                <div class="info-box">
                    <h2 class="sub-title">Thông tin cửa hàng</h2>
                    <p class="info-text">
                        Zero Watch không chỉ bán đồng hồ, chúng tôi bán sự đẳng cấp và thời gian. Hãy ghé thăm showroom để trải nghiệm trực tiếp.
                    </p>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div>
                            <span class="label">Địa chỉ:</span>
                            <div class="value">QTSC 9 Building, Đ. Tô Ký, Tân Chánh Hiệp, Q.12, Tp.Hồ Chí Minh</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.21c.28-.26.36-.65.25-1C8.7 6.33 8.5 5.13 8.5 3.9c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/></svg>
                        </div>
                        <div>
                            <span class="label">Hotline:</span>
                            <div class="value highlight-text">0397 337 423</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div>
                            <span class="label">Email:</span>
                            <div class="value">angtenchill@gmail.com</div>
                        </div>
                    </div>
                </div>

                <!-- Google Map Container -->
                <div class="map-container" style="margin-top: 20px; height: 350px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.4205948965825!2d106.623485775704!3d10.855580057726354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752a2072046803%3A0xc3f8e58988226065!2sQTSC%209%20Building!5e0!3m2!1svi!2s!4v1708681234567!5m2!1svi!2s" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            <!-- Cột Phải: Form Liên Hệ Glassmorphism -->
            <div class="contact-form-col">
                <form class="glass-form" action="<?= BASE_URL ?>index.php/contact/send" method="POST">
                    <h2 class="form-title">Gửi tin nhắn</h2>
                    
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" id="name" name="name" class="custom-input" placeholder="Nhập tên của bạn">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="custom-input" placeholder="example@email.com">
                    </div>

                    <div class="form-group">
                        <label for="subject">Tiêu đề</label>
                        <select id="subject" name="subject" class="custom-input custom-select">
                            <option value="">Chọn vấn đề cần hỗ trợ</option>
                            <option value="buy">Tư vấn mua hàng</option>
                            <option value="warranty">Bảo hành & Sửa chữa</option>
                            <option value="collab">Hợp tác kinh doanh</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Nội dung</label>
                        <textarea id="message" name="message" rows="5" class="custom-input" placeholder="Nhập nội dung tin nhắn..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn section-action">Gửi ngay >></button>
                </form>
            </div>

        </div>
    </section>
</main>