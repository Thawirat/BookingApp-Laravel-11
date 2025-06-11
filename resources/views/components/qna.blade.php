<div class="accordion" id="faqAccordion">
    <!-- FAQ 1 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" :class="{ 'collapsed': openFaq !== 1 }"
                @click="openFaq === 1 ? openFaq = null : openFaq = 1" type="button">
                ทำการจองห้องได้ล่วงหน้ากี่วัน?
            </button>
        </h2>
        <div class="accordion-collapse" x-show="openFaq === 1" x-collapse aria-labelledby="headingOne">
            <div class="accordion-body">
                สามารถทำการจองล่วงหน้าได้ไม่เกิน 30 วัน
            </div>
        </div>
    </div>

    <!-- FAQ 2 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button" :class="{ 'collapsed': openFaq !== 2 }"
                @click="openFaq === 2 ? openFaq = null : openFaq = 2" type="button">
                มีค่าใช้จ่ายในการจองห้องหรือไม่?
            </button>
        </h2>
        <div class="accordion-collapse" x-show="openFaq === 2" x-collapse aria-labelledby="headingTwo">
            <div class="accordion-body">
                ค่าใช้จ่ายขึ้นอยู่กับประเภทห้องและระยะเวลาการใช้งาน
                โปรดตรวจสอบราคาในหน้ารายละเอียดห้อง
            </div>
        </div>
    </div>

    <!-- FAQ 3 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button" :class="{ 'collapsed': openFaq !== 3 }"
                @click="openFaq === 3 ? openFaq = null : openFaq = 3" type="button">
                สามารถยกเลิกการจองได้หรือไม่?
            </button>
        </h2>
        <div class="accordion-collapse" x-show="openFaq === 3" x-collapse aria-labelledby="headingThree">
            <div class="accordion-body">
                สามารถยกเลิกการจองได้ผ่านระบบออนไลน์ โดยต้องยกเลิกก่อนวันใช้งานอย่างน้อย 24
                ชั่วโมง
            </div>
        </div>
    </div>

    <!-- FAQ 4 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button" :class="{ 'collapsed': openFaq !== 4 }"
                @click="openFaq === 4 ? openFaq = null : openFaq = 4" type="button">
                ต้องชำระเงินอย่างไร?
            </button>
        </h2>
        <div class="accordion-collapse" x-show="openFaq === 4" x-collapse aria-labelledby="headingFour">
            <div class="accordion-body">
                สามารถชำระเงินผ่านบัตรเครดิต/เดบิต
                หรือชำระเงินสดที่สำนักงานการเงินของมหาวิทยาลัย
            </div>
        </div>
    </div>

    <!-- FAQ 5 -->
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button" :class="{ 'collapsed': openFaq !== 5 }"
                @click="openFaq === 5 ? openFaq = null : openFaq = 5" type="button">
                มีห้องให้เลือกกี่ประเภท?
            </button>
        </h2>
        <div class="accordion-collapse" x-show="openFaq === 5" x-collapse aria-labelledby="headingFive">
            <div class="accordion-body">
                มีห้องให้เลือกหลากหลายประเภท เช่น ห้องเรียนขนาดเล็ก ห้องประชุม ห้องสัมมนา
                และห้องอเนกประสงค์
            </div>
        </div>
    </div>
</div>
