<!-- Footer -->
<!--footer class="footer">
            <div class="footer-content">
                <div class="footer-brand">
                    <span class="footer-brand-mark">
                        <img src="{{ asset('images/logo.webp') }}" alt="AppDashboard">
                    </span>
                    <div>
                        <strong>AppDashboard</strong>
                        <span>Operational admin workspace</span>
                    </div>
                </div>

                <div class="footer-links">
                    <a href="#">About</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Contact</a>
                </div>

                <div class="footer-meta">
                    <div class="footer-copyright">
                        © 2026 <a href="#">AppDashboard</a>. All Rights Reserved.
                    </div>
                    <div class="footer-credits">
                        <div class="credits">
                            
                            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer-->
</main>

<style>
    /* Animasi saat toast muncul */
    .toastify.toast-animasi {
        animation: toastMasuk 0.4s ease-out both;
    }

    /* Animasi saat toast ditutup */
    .toastify.toast-animasi.toast-keluar {
        animation: toastKeluar 0.3s ease-in both;
    }

    @keyframes toastMasuk {
        from {
            opacity: 0;
            transform: translate3d(40px, 0, 0);
        }

        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    @keyframes toastKeluar {
        from {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }

        to {
            opacity: 0;
            transform: translate3d(40px, 0, 0);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .toastify.toast-animasi,
        .toastify.toast-animasi.toast-keluar {
            animation: none;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@if (session('success'))
    <script>
        Toastify({
            text: @json(session('success')),
            duration: 3500,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            className: "toast-animasi",
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
                borderRadius: "8px"
            },
            callback: function() {
                // Toast sudah selesai ditampilkan.
            }
        }).showToast();
    </script>
@endif


<!-- Back to Top -->
<a href="#" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

@vite(['resources/js/bootstrap.bundle.min.js', 'resources/js/apexcharts.min.js', 'resources/js/chart.umd.js', 'resources/js/echarts.min.js', 'resources/js/simple-datatables.js', 'resources/js/quill.js', 'resources/js/tinymce.min.js', 'resources/js/choices.min.js', 'resources/js/flatpickr.min.js', 'resources/js/validate.js', 'resources/js/theme.js', 'resources/js/main.js', 'resources/js/apps-sidebar-toggle.js'])

<!-- Vendor JS Files
    <script data-cfasync="false" src="js/email-decode.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/apexcharts.min.js"></script>
    <script src="js/chart.umd.js"></script>
    <script src="js/echarts.min.js"></script>
    <script src="js/simple-datatables.js"></script>
    <script src="js/quill.js"></script>
    <script src="js/tinymce.min.js"></script>
    <script src="js/choices.min.js"></script>
    <script src="js/flatpickr.min.js"></script>
    <script src="js/validate.js"></script>-->

<!-- Template Main JS Files
    <script src="js/theme.js"></script>
    <script src="js/main.js"></script> -->

<!-- App Sidebar Toggle (for app pages with sidebars)
    <script src="js/apps-sidebar-toggle.js"></script> -->

<script>
    // Revenue Overview Chart
    document.addEventListener('DOMContentLoaded', function() {
        const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-color')
            .trim();
        const successColor = getComputedStyle(document.documentElement).getPropertyValue('--success-color')
            .trim();
        const warningColor = getComputedStyle(document.documentElement).getPropertyValue('--warning-color')
            .trim();
        const borderColor = getComputedStyle(document.documentElement).getPropertyValue('--border-color')
            .trim();
        const mutedColor = getComputedStyle(document.documentElement).getPropertyValue('--muted-color').trim();
        const options = {
            series: [{
                name: 'Revenue',
                data: [4200, 5800, 4900, 6200, 5100, 7400, 6800, 8100, 7200, 9500, 8900, 10200]
            }, {
                name: 'Expenses',
                data: [2800, 3200, 2900, 3400, 3100, 3800, 3500, 4200, 3900, 4800, 4200, 5100]
            }, {
                name: 'Customers',
                data: [120, 480, 750, 920, 1000, 1200, 1550, 1850, 2280, 2640, 3100, 3800]
            }],
            chart: {
                type: 'area',
                height: 330,
                fontFamily: 'inherit',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: [accentColor, successColor, warningColor],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.34,
                    opacityTo: 0.06,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                    'Dec'
                ],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: mutedColor,
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: mutedColor,
                        fontSize: '12px'
                    },
                    formatter: function(value) {
                        return '$' + (value / 1000).toFixed(1) + 'k';
                    }
                }
            },
            grid: {
                borderColor: borderColor,
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: false
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '13px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 4
                },
                itemMargin: {
                    horizontal: 12
                }
            },
            tooltip: {
                y: {
                    formatter: function(value, {
                        seriesIndex
                    }) {
                        if (seriesIndex === 2) {
                            return value.toLocaleString() + ' customers';
                        }
                        return '$' + value.toLocaleString();
                    }
                }
            }
        };
        const chart = new ApexCharts(document.querySelector('#revenueChart'), options);
        chart.render();
        document.addEventListener('themeChanged', function() {
            const newBorderColor = getComputedStyle(document.documentElement).getPropertyValue(
                '--border-color').trim();
            const newMutedColor = getComputedStyle(document.documentElement).getPropertyValue(
                '--muted-color').trim();
            chart.updateOptions({
                grid: {
                    borderColor: newBorderColor
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: newMutedColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: newMutedColor
                        }
                    }
                }
            });
        });
    });
</script>
<script type="module"
    src="https://static.cloudflareinsights.com/beacon.min.js/v31edd6df95cf4e85bb4c19e7a9bdbcba1788362987495"
    data-cf-beacon="{" version":"2024.11.0","token":"68c5ca450bae485a842ff76066d69420","spa":2}"=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Basic date picker
        flatpickr('[data-picker="date"]', {
            dateFormat: 'd-m-Y',
            allowInput: true
        });
        // Date with today as default
        flatpickr('[data-default-date="today"]', {
            dateFormat: 'd-m-Y',
            defaultDate: 'today',
            allowInput: true
        });
        // Custom format DD/MM/YYYY
        flatpickr('#customFormat', {
            dateFormat: 'd/m/Y',
            allowInput: true
        });
        // Long format
        flatpickr('#longFormat', {
            dateFormat: 'F j, Y',
            allowInput: true
        });
        // Time picker 24h
        flatpickr('[data-picker="time"]', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            time_24hr: true,
            allowInput: true
        });
        // Time picker 12h
        flatpickr('[data-picker="time-12"]', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'h:i K',
            time_24hr: false,
            allowInput: true
        });
        // Time with seconds
        flatpickr('[data-picker="time-seconds"]', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i:S',
            time_24hr: true,
            enableSeconds: true,
            allowInput: true
        });
        // Preselected time
        flatpickr('#preselectedTime', {
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            time_24hr: true,
            defaultDate: '09:30'
        });
        // DateTime picker
        flatpickr('[data-picker="datetime"]', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            allowInput: true
        });
        // DateTime 12h
        flatpickr('[data-picker="datetime-12"]', {
            enableTime: true,
            dateFormat: 'Y-m-d h:i K',
            time_24hr: false,
            allowInput: true
        });
        // DateTime with 15min increment
        flatpickr('[data-picker="datetime-15"]', {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            minuteIncrement: 15,
            allowInput: true
        });
        // Date range
        flatpickr('[data-picker="range"]', {
            mode: 'range',
            dateFormat: 'd-m-Y',
            allowInput: true
        });
        // Range with preset
        flatpickr('#dateRangePreset', {
            mode: 'range',
            dateFormat: 'd-m-Y',
            defaultDate: [new Date(), new Date(Date.now() + 7 * 24 * 60 * 60 * 1000)]
        });
        // Linked range pickers
        const rangeStart = flatpickr('#rangeStart', {
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates) {
                rangeEnd.set('minDate', selectedDates[0]);
            }
        });
        const rangeEnd = flatpickr('#rangeEnd', {
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates) {
                rangeStart.set('maxDate', selectedDates[0]);
            }
        });
        // Multiple dates
        flatpickr('[data-picker="multiple"]', {
            mode: 'multiple',
            dateFormat: 'd-m-Y',
            conjunction: ', '
        });
        // Multiple dates with limit
        flatpickr('[data-picker="multiple-limit"]', {
            mode: 'multiple',
            dateFormat: 'd-m-Y',
            conjunction: ', ',
            onReady: function() {
                this.config.maxDates = 5;
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 5) {
                    selectedDates.pop();
                    instance.setDate(selectedDates);
                }
            }
        });
        // Week picker
        flatpickr('[data-picker="week"]', {
            weekNumbers: true,
            dateFormat: 'Y-W\\WW',
            allowInput: true
        });
        // Week range
        flatpickr('[data-picker="week-range"]', {
            mode: 'range',
            weekNumbers: true,
            dateFormat: 'd-m-Y'
        });
        // Min date today
        flatpickr('[data-picker="min-today"]', {
            dateFormat: 'd-m-Y',
            minDate: 'today',
            allowInput: true
        });
        // Max date today
        flatpickr('[data-picker="max-today"]', {
            dateFormat: 'd-m-Y',
            maxDate: 'today',
            allowInput: true
        });
        // Range window (30 days)
        flatpickr('[data-picker="range-window"]', {
            dateFormat: 'd-m-Y',
            minDate: 'today',
            maxDate: new Date().fp_incr(30)
        });
        // No weekends
        flatpickr('[data-picker="no-weekends"]', {
            dateFormat: 'd-m-Y',
            disable: [
                function(date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ]
        });
        // Specific disabled dates
        flatpickr('[data-picker="disabled-dates"]', {
            dateFormat: 'd-m-Y',
            disable: [
                new Date().fp_incr(2),
                new Date().fp_incr(5),
                new Date().fp_incr(10)
            ]
        });
        // Only specific dates enabled
        flatpickr('[data-picker="enabled-dates"]', {
            dateFormat: 'd-m-Y',
            enable: [
                new Date(),
                new Date().fp_incr(1),
                new Date().fp_incr(3),
                new Date().fp_incr(7),
                new Date().fp_incr(14)
            ]
        });
        // Inline calendar
        const inlineCalendar = flatpickr('#inlineCalendar', {
            inline: true,
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates, dateStr) {
                document.getElementById('inlineCalendarOutput').value = dateStr;
            }
        });
        // Inline range calendar
        const inlineRangeCalendar = flatpickr('#inlineRangeCalendar', {
            inline: true,
            mode: 'range',
            dateFormat: 'd-m-Y',
            onChange: function(selectedDates, dateStr) {
                document.getElementById('inlineRangeOutput').value = dateStr;
            }
        });
        // Month picker
        flatpickr('[data-picker="month"]', {
            plugins: [],
            dateFormat: 'F Y',
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
                // Keep only month view
            }
        });
        // Year picker - using month picker with year view
        flatpickr('[data-picker="year"]', {
            dateFormat: 'Y',
            disableMobile: true
        });
        // Month & Year picker
        flatpickr('[data-picker="month-year"]', {
            dateFormat: 'F Y',
            disableMobile: true
        });
        // DOB picker (18+ years)
        const maxDOB = new Date();
        maxDOB.setFullYear(maxDOB.getFullYear() - 18);
        flatpickr('[data-picker="dob"]', {
            dateFormat: 'd-m-Y',
            maxDate: maxDOB,
            defaultDate: maxDOB
        });
        // Clear buttons
        document.querySelectorAll('.picker-clear-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input');
                if (input._flatpickr) {
                    input._flatpickr.clear();
                }
            });
        });
        // Report period presets
        document.querySelectorAll('.preset-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const preset = this.dataset.preset;
                const picker = document.getElementById('reportPeriod')._flatpickr;
                const today = new Date();
                let startDate, endDate;
                switch (preset) {
                    case 'today':
                        startDate = endDate = today;
                        break;
                    case 'week':
                        const dayOfWeek = today.getDay();
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - dayOfWeek);
                        endDate = new Date(startDate);
                        endDate.setDate(startDate.getDate() + 6);
                        break;
                    case 'month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        break;
                    case 'year':
                        startDate = new Date(today.getFullYear(), 0, 1);
                        endDate = new Date(today.getFullYear(), 11, 31);
                        break;
                }
                picker.setDate([startDate, endDate]);
            });
        });
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all Choices.js instances
        const selectElements = document.querySelectorAll('[data-choices]');
        selectElements.forEach(function(el) {
            // Skip if already initialized or is the loading example
            if (el.id === 'loadingSelect') return;
            const config = {
                removeItemButton: el.dataset.choicesRemoveitem === 'true',
                searchEnabled: el.dataset.choicesSearch === 'true',
                shouldSort: el.dataset.choicesSort !== 'false',
                placeholderValue: el.dataset.choicesPlaceholder || null,
                maxItemCount: el.dataset.choicesMaxitems ? parseInt(el.dataset.choicesMaxitems) :
                    -1,
                duplicateItemsAllowed: el.dataset.choicesUnique !== 'true',
                editItems: el.dataset.choicesText === 'true',
                addItems: el.dataset.choicesText === 'true',
                searchPlaceholderValue: 'Search...',
                itemSelectText: '',
                classNames: {
                    containerOuter: 'choices',
                    containerInner: 'choices__inner',
                    input: 'choices__input',
                    inputCloned: 'choices__input--cloned',
                    list: 'choices__list',
                    listItems: 'choices__list--multiple',
                    listSingle: 'choices__list--single',
                    listDropdown: 'choices__list--dropdown',
                    item: 'choices__item',
                    itemSelectable: 'choices__item--selectable',
                    itemDisabled: 'choices__item--disabled',
                    itemChoice: 'choices__item--choice',
                    placeholder: 'choices__placeholder',
                    group: 'choices__group',
                    groupHeading: 'choices__heading',
                    button: 'choices__button',
                    activeState: 'is-active',
                    focusState: 'is-focused',
                    openState: 'is-open',
                    disabledState: 'is-disabled',
                    highlightedState: 'is-highlighted',
                    selectedState: 'is-selected',
                    flippedState: 'is-flipped',
                    loadingState: 'is-loading',
                }
            };
            // Handle text/email input differently
            if (el.tagName === 'INPUT') {
                config.delimiter = ',';
                config.editItems = true;
                config.addItems = true;
                config.removeItemButton = true;
                if (el.dataset.choicesEmail === 'true') {
                    config.addItemFilter = function(value) {
                        if (!value) return false;
                        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        return regex.test(value);
                    };
                }
            }
            new Choices(el, config);
        });
        // Loading example
        const loadingSelect = document.getElementById('loadingSelect');
        let loadingChoices = null;
        if (loadingSelect) {
            loadingChoices = new Choices(loadingSelect, {
                searchEnabled: false,
                itemSelectText: '',
            });
            // Simulate loading data
            setTimeout(function() {
                loadingChoices.setChoices([{
                        value: 'option1',
                        label: 'Loaded Option 1'
                    },
                    {
                        value: 'option2',
                        label: 'Loaded Option 2'
                    },
                    {
                        value: 'option3',
                        label: 'Loaded Option 3'
                    },
                    {
                        value: 'option4',
                        label: 'Loaded Option 4'
                    },
                ], 'value', 'label', true);
            }, 2000);
            // Load/Clear buttons
            const loadBtn = document.getElementById('loadDataBtn');
            const clearBtn = document.getElementById('clearDataBtn');
            if (loadBtn) {
                loadBtn.addEventListener('click', function() {
                    loadingChoices.clearStore();
                    loadingChoices.setChoices([{
                        value: '',
                        label: 'Loading...',
                        disabled: true
                    }, ], 'value', 'label', true);
                    setTimeout(function() {
                        loadingChoices.setChoices([{
                                value: 'new1',
                                label: 'Fresh Option 1'
                            },
                            {
                                value: 'new2',
                                label: 'Fresh Option 2'
                            },
                            {
                                value: 'new3',
                                label: 'Fresh Option 3'
                            },
                        ], 'value', 'label', true);
                    }, 1500);
                });
            }
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    loadingChoices.clearStore();
                    loadingChoices.setChoices([{
                        value: '',
                        label: 'Select an option...',
                        placeholder: true
                    }, ], 'value', 'label', true);
                });
            }
        }
        // Form validation example
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all dropzones
        const dropzones = document.querySelectorAll('.upload-dropzone');
        dropzones.forEach(function(dropzone) {
            const input = dropzone.querySelector('input[type="file"]');
            if (!input) return;
            // Click to browse
            dropzone.addEventListener('click', function(e) {
                if (e.target.tagName !== 'INPUT') {
                    input.click();
                }
            });
            // Drag events
            ['dragenter', 'dragover'].forEach(function(eventName) {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                });
            });
            ['dragleave', 'drop'].forEach(function(eventName) {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                });
            });
            // Drop handler
            dropzone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;
                if (files.length) {
                    input.files = files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
        // File list display for dropzone1
        const dropzoneInput1 = document.getElementById('dropzoneInput1');
        const fileList1 = document.getElementById('fileList1');
        if (dropzoneInput1 && fileList1) {
            dropzoneInput1.addEventListener('change', function() {
                displayFileList(this.files, fileList1);
            });
        }
        // Image preview for dropzone2
        const dropzoneInput2 = document.getElementById('dropzoneInputBuktiTransaksi');
        const imagePreview2 = document.getElementById('imagePreviewBuktiTransaksi');
        if (dropzoneInput2 && imagePreview2) {
            dropzoneInput2.addEventListener('change', function() {
                displayImagePreviews(this.files, imagePreview2);
            });
        }
        // Image preview for dropzone2
        const dropzoneInputNota = document.getElementById('dropzoneInputNota');
        const imagePreviewNota = document.getElementById('imagePreviewNota');
        if (dropzoneInputNota && imagePreviewNota) {
            dropzoneInputNota.addEventListener('change', function() {
                displayImagePreviews(this.files, imagePreviewNota);
            });
        }
        // File list for dropzone3
        const dropzoneInput3 = document.getElementById('dropzoneInput3');
        const fileList3 = document.getElementById('fileList3');
        if (dropzoneInput3 && fileList3) {
            dropzoneInput3.addEventListener('change', function() {
                displayFileList(this.files, fileList3);
            });
        }
        // Button uploads
        const buttonUpload1 = document.getElementById('buttonUpload1');
        const buttonUpload2 = document.getElementById('buttonUpload2');
        const buttonFileList = document.getElementById('buttonFileList');
        if (buttonUpload1 && buttonFileList) {
            buttonUpload1.addEventListener('change', function() {
                displayFileList(this.files, buttonFileList);
            });
        }
        if (buttonUpload2 && buttonFileList) {
            buttonUpload2.addEventListener('change', function() {
                displayFileList(this.files, buttonFileList);
            });
        }
        // Avatar uploads
        const avatars = document.querySelectorAll('.upload-avatar');
        avatars.forEach(function(avatar) {
            const input = avatar.querySelector('input[type="file"]');
            const img = avatar.querySelector('.upload-avatar-img');
            avatar.addEventListener('click', function() {
                input.click();
            });
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
        // Avatar with button
        const avatarInput3 = document.getElementById('avatarInput3');
        const avatarPreview3 = document.getElementById('avatarPreview3');
        const avatarRemove3 = document.getElementById('avatarRemove3');
        if (avatarInput3 && avatarPreview3) {
            avatarInput3.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview3.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        if (avatarRemove3 && avatarPreview3) {
            avatarRemove3.addEventListener('click', function() {
                avatarPreview3.src = 'assets/img/avatars/avatar-3.webp';
                if (avatarInput3) avatarInput3.value = '';
            });
        }
        // Cover upload
        const coverUpload = document.getElementById('coverUpload');
        if (coverUpload) {
            const input = coverUpload.querySelector('input[type="file"]');
            const img = coverUpload.querySelector('.upload-cover-img');
            coverUpload.addEventListener('click', function() {
                input.click();
            });
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        // Logo upload
        const logoUpload = document.getElementById('logoUpload');
        if (logoUpload) {
            const input = logoUpload.querySelector('input[type="file"]');
            const placeholder = logoUpload.querySelector('.upload-logo-placeholder');
            logoUpload.addEventListener('click', function() {
                input.click();
            });
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        placeholder.innerHTML = '<img src="' + e.target.result + '" alt="Logo">';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        // Gallery add button
        const galleryAddBtn = document.getElementById('galleryAddBtn');
        const galleryInput = document.getElementById('galleryInput');
        if (galleryAddBtn && galleryInput) {
            galleryAddBtn.addEventListener('click', function() {
                galleryInput.click();
            });
        }
        // Helper function to display file list
        function displayFileList(files, container) {
            container.innerHTML = '';
            Array.from(files).forEach(function(file) {
                const item = document.createElement('div');
                item.className = 'upload-file-item';
                item.innerHTML = `
        <div class="upload-file-icon">
          <i class="bi ${getFileIcon(file.type)}"></i>
        </div>
        <div class="upload-file-info">
          <span class="upload-file-name">${file.name}</span>
          <span class="upload-file-size">${formatFileSize(file.size)}</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger upload-file-remove">
          <i class="bi bi-x-lg"></i>
        </button>
      `;
                item.querySelector('.upload-file-remove').addEventListener('click', function() {
                    item.remove();
                });
                container.appendChild(item);
            });
        }
        // Helper function to display image previews
        function displayImagePreviews(files, container) {
            container.innerHTML = '';
            Array.from(files).forEach(function(file) {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'upload-image-item';
                    item.innerHTML = `
          <img src="${e.target.result}" alt="${file.name}">
          <button type="button" class="upload-image-remove">
            <i class="bi bi-x-lg"></i>
          </button>
        `;
                    item.querySelector('.upload-image-remove').addEventListener('click',
                        function() {
                            item.remove();
                        });
                    container.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        }
        // Helper function to get file icon
        function getFileIcon(type) {
            if (type.startsWith('image/')) return 'bi-file-earmark-image text-primary';
            if (type === 'application/pdf') return 'bi-file-earmark-pdf text-danger';
            if (type.includes('word')) return 'bi-file-earmark-word text-info';
            if (type.includes('excel') || type.includes('spreadsheet'))
                return 'bi-file-earmark-excel text-success';
            if (type.includes('zip') || type.includes('rar')) return 'bi-file-earmark-zip text-warning';
            if (type.startsWith('video/')) return 'bi-file-earmark-play text-purple';
            if (type.startsWith('audio/')) return 'bi-file-earmark-music text-pink';
            return 'bi-file-earmark text-secondary';
        }
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        // Form validation
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.reconciliation-form');

        function formatRupiah(angka) {
            return 'Rp' + Number(angka || 0).toLocaleString('id-ID');
        }

        function getNominal(card) {
            const input = card.querySelector('.nominal-input');
            return Number(input ? input.value : card.dataset.nominal) || 0;
        }

        // EDIT CARD EXCEL
        document.querySelectorAll('.reconciliation-excel-row .edit-row-btn')
            .forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.reconciliation-excel-row');
                    if (!card) return;

                    card.querySelectorAll('.excel-edit-field').forEach(field => {
                        field.removeAttribute('readonly');
                    });

                    this.classList.add('d-none');
                    card.querySelector('.save-row-btn')?.classList.remove('d-none');
                });
            });

        // SELESAI EDIT: KEMBALI KE READONLY
        document.querySelectorAll('.reconciliation-excel-row .save-row-btn')
            .forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.reconciliation-excel-row');
                    if (!card) return;

                    card.querySelectorAll('.excel-edit-field').forEach(field => {
                        field.setAttribute('readonly', 'readonly');
                    });

                    this.classList.add('d-none');
                    card.querySelector('.edit-row-btn')?.classList.remove('d-none');

                    updateNominal(card);
                    updateAllSummaries();
                    highlightNominalMatches();
                });
            });

        // SINKRONKAN NOMINAL TERBARU
        function updateNominal(card) {
            if (!card) return;

            const nominal = getNominal(card);
            card.dataset.nominal = nominal;

            const checkbox = card.querySelector('.reconciliation-checkbox');
            if (checkbox) {
                checkbox.dataset.nominal = nominal;
            }
        }

        document.querySelectorAll('.nominal-input').forEach(input => {
            input.addEventListener('input', function() {
                const card = this.closest('.reconciliation-excel-row');

                updateNominal(card);
                updateAllSummaries();
                highlightNominalMatches();
            });
        });

        // RINGKASAN TOTAL DAN STATUS HEADER
        function updateSummary(form) {
            const checkboxes = form.querySelectorAll('.reconciliation-checkbox');
            const countElement = form.querySelector('.selected-count');
            const totalElement = form.querySelector('.selected-total');
            const summary = form.querySelector('.reconciliation-summary');

            if (!summary || !countElement || !totalElement) return;

            let jumlah = 0;
            let total = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    jumlah++;
                    total += Number(checkbox.dataset.nominal || 0);
                }
            });

            countElement.textContent = jumlah;
            totalElement.textContent = formatRupiah(total);

            const databaseTotal = Number(summary.dataset.databaseTotal || 0);
            const sesuai = total === databaseTotal;

            totalElement.classList.toggle('text-success', sesuai);
            totalElement.classList.toggle('text-danger', !sesuai);

            const warning = summary.querySelector('.comparison-warning');
            const comparisonStatus = summary.querySelector('.comparison-status');

            if (warning) {
                warning.style.display = total > databaseTotal ? 'block' : 'none';
            }

            if (comparisonStatus) {
                comparisonStatus.textContent = sesuai ?
                    'Nominal sudah sesuai.' :
                    'Nominal belum sesuai.';

                comparisonStatus.className = sesuai ?
                    'small comparison-status text-success' :
                    'small comparison-status text-danger';
            }

            const dateCard = form.closest('.reconciliation-date-card');
            const badge = dateCard?.querySelector('.reconciliation-date-status');

            if (badge) {
                badge.classList.toggle('text-bg-success', sesuai);
                badge.classList.toggle('text-bg-danger', !sesuai);
                badge.textContent = sesuai ? 'Nominal Sesuai' : 'Belum Sesuai';
            }
            cekKesiapanLanjutkan();
        }

        function updateAllSummaries() {
            forms.forEach(form => updateSummary(form));
        }

        // HIGHLIGHT NOMINAL PER TANGGAL
        function highlightNominalMatches() {
            document.querySelectorAll('.reconciliation-date-card').forEach(dateCard => {
                const excelCards = [
                    ...dateCard.querySelectorAll('.reconciliation-excel-row')
                ];

                const databaseCards = [
                    ...dateCard.querySelectorAll('.reconciliation-database-row')
                ];

                const databaseCounts = new Map();
                const checkedCounts = new Map();

                databaseCards.forEach(card => {
                    const nominal = String(getNominal(card));

                    databaseCounts.set(
                        nominal,
                        (databaseCounts.get(nominal) || 0) + 1
                    );
                });

                excelCards.forEach(card => {
                    const checkbox = card.querySelector('.reconciliation-checkbox');

                    if (checkbox?.checked) {
                        const nominal = String(getNominal(card));

                        checkedCounts.set(
                            nominal,
                            (checkedCounts.get(nominal) || 0) + 1
                        );
                    }
                });

                excelCards.forEach(card => {
                    const nominal = String(getNominal(card));
                    const jumlahDatabase = databaseCounts.get(nominal) || 0;
                    const jumlahChecked = checkedCounts.get(nominal) || 0;

                    card.classList.toggle(
                        'nominal-match',
                        jumlahDatabase > jumlahChecked
                    );
                });
            });
        }

        // EVENT CHECKBOX
        document.querySelectorAll('.reconciliation-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateAllSummaries();
                highlightNominalMatches();
            });
        });

        // SIMPAN DRAFT VIA AJAX
        forms.forEach(form => {
            const statusElement = form.querySelector('.save-status');
            const button = form.querySelector('button[type="submit"]');

            updateSummary(form);

            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                if (button) button.disabled = true;

                if (statusElement) {
                    statusElement.textContent = 'Menyimpan...';
                    statusElement.className = 'save-status small mt-2 text-muted';
                }

                const formData = new FormData(form);

                // Checkbox yang dilepas harus tidak masuk checked_ids[],
                // tetapi item_ids[] tetap dikirim untuk menyimpan status false.
                formData.delete('checked_ids[]');

                form.querySelectorAll('.reconciliation-checkbox').forEach(checkbox => {
                    if (checkbox.checked) {
                        formData.append('checked_ids[]', checkbox.value);
                    }
                });

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(
                            result.message || 'Gagal menyimpan perubahan.'
                        );
                    }

                    sessionStorage.setItem(
                        'reconciliation-scroll',
                        window.scrollY
                    );

                    sessionStorage.setItem(
                        'reconciliation-saved',
                        result.message || 'Draft berhasil disimpan.'
                    );

                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        window.location.reload();
                    }
                } catch (error) {
                    if (statusElement) {
                        statusElement.textContent =
                            error.message || 'Gagal menyimpan. Silakan coba kembali.';
                        statusElement.className = 'save-status small mt-2 text-danger';
                    }
                } finally {
                    if (button) button.disabled = false;
                }
            });
        });

        // PULIHKAN PESAN DAN SCROLL
        const savedScroll = sessionStorage.getItem('reconciliation-scroll');
        const savedMessage = sessionStorage.getItem('reconciliation-saved');

        if (savedMessage !== null) {
            sessionStorage.removeItem('reconciliation-saved');

            const status = document.querySelector('.save-status');

            if (status) {
                status.textContent = savedMessage;
                status.className = 'save-status small mt-2 text-success';
            }
        }

        if (savedScroll !== null) {
            sessionStorage.removeItem('reconciliation-scroll');

            window.addEventListener('load', function() {
                window.scrollTo(0, Number(savedScroll));
            });
        }

        // HITUNG SEMUA SAAT HALAMAN PERTAMA KALI DIBUKA
        updateAllSummaries();
        highlightNominalMatches();


        // BUKA FORM EDIT TUJUAN
        document.querySelectorAll('.edit-database-tujuan-btn').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.reconciliation-database-row');
                if (!card) return;

                card.querySelector('.database-tujuan-text')?.classList.add('d-none');
                card.querySelector('.database-edit-field')?.classList.remove('d-none');

                this.classList.add('d-none');
            });
        });

        // BATAL EDIT TUJUAN
        document.querySelectorAll('.cancel-database-tujuan-btn').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.reconciliation-database-row');
                if (!card) return;

                const editField = card.querySelector('.database-edit-field');
                const select = card.querySelector('.database-tujuan-input');
                const status = card.querySelector('.database-tujuan-status');

                // Kembalikan pilihan ke tujuan yang terakhir tersimpan
                if (select) {
                    select.value = select.dataset.savedValue || '';
                }

                editField?.classList.add('d-none');
                card.querySelector('.database-tujuan-text')?.classList.remove('d-none');
                card.querySelector('.edit-database-tujuan-btn')?.classList.remove('d-none');

                if (status) status.textContent = '';
            });
        });

        // SIMPAN TUJUAN KE DATABASE
        document.querySelectorAll('.save-database-tujuan-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const card = this.closest('.reconciliation-database-row');
                const select = card?.querySelector('.database-tujuan-input');
                const status = card?.querySelector('.database-tujuan-status');

                if (!card || !select || !status) return;

                if (!select.value) {
                    status.textContent = 'Pilih tujuan transaksi terlebih dahulu.';
                    status.className = 'database-tujuan-status small mt-2 text-danger';
                    return;
                }

                button.disabled = true;
                status.textContent = 'Menyimpan...';
                status.className = 'database-tujuan-status small mt-2 text-muted';

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ?.content;

                    if (!csrfToken) {
                        throw new Error(
                            'CSRF token tidak ditemukan. Periksa header.blade.php.');
                    }

                    const response = await fetch(button.dataset.url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            id_tujuan_transaksi: select.value
                        })
                    });

                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Gagal menyimpan tujuan.');
                    }

                    const namaTujuan = select.options[select.selectedIndex].text;

                    card.querySelector('.database-tujuan-text').textContent = namaTujuan;
                    card.querySelector('.database-tujuan-text').classList.remove('d-none');
                    card.querySelector('.database-edit-field').classList.add('d-none');
                    card.querySelector('.edit-database-tujuan-btn')?.classList.remove(
                        'd-none');

                    select.dataset.savedValue = select.value;

                    status.textContent = result.message || 'Tujuan berhasil diperbarui.';
                    status.className = 'database-tujuan-status small mt-2 text-success';

                } catch (error) {
                    status.textContent = error.message || 'Gagal menyimpan tujuan.';
                    status.className = 'database-tujuan-status small mt-2 text-danger';
                } finally {
                    button.disabled = false;
                }
            });
        });

        // Simpan pilihan awal untuk fungsi Batal
        document.querySelectorAll('.database-tujuan-input').forEach(select => {
            select.dataset.savedValue = select.value;
        });


        function cekKesiapanLanjutkan() {
            const btn = document.getElementById('btnLanjutkan');

            if (!btn) return;

            const dateCards = Array.from(
                document.querySelectorAll('.reconciliation-date-card')
            );

            const semuaSesuai = dateCards.length > 0 &&
                dateCards.every(dateCard => {
                    const badge = dateCard.querySelector(
                        '.reconciliation-date-status'
                    );

                    return badge &&
                        badge.textContent.trim() === 'Nominal Sesuai';
                });

            btn.disabled = !semuaSesuai;
        }
        updateAllSummaries();
        highlightNominalMatches();
        cekKesiapanLanjutkan();

    });
</script>





<script>
    document.querySelectorAll('.akun-search').forEach(function(input) {
        input.addEventListener('input', function() {
            const select = this.nextElementSibling;
            const keyword = this.value.toLowerCase().trim();

            Array.from(select.options).forEach(function(option, index) {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                option.hidden = !option.text.toLowerCase().includes(keyword);
            });

            if (
                select.selectedOptions.length &&
                select.selectedOptions[0].hidden
            ) {
                select.value = '';
            }
        });
    });
</script>
<script>
    document.querySelectorAll('.akun-select, .sumber-dana-select').forEach(function(select) {
        select.addEventListener('change', async function() {
            const card = this.closest('.alert');
            const form = document.getElementById('formTujuan');
            const isSumberDana = this.classList.contains('sumber-dana-select');

            const status = card.querySelector(
                isSumberDana ? '.sumber-dana-status' : '.autosave-status'
            );

            const itemIds = Array.from(
                card.querySelectorAll('input[name^="item_ids["]')
            ).map(input => input.value);

            if (!this.value) {
                status.textContent = isSumberDana ?
                    'Pilih sumber dana terlebih dahulu.' :
                    'Pilih tujuan terlebih dahulu.';
                return;
            }

            if (itemIds.length === 0) {
                status.textContent = 'Gagal: ID baris draft tidak ditemukan.';
                return;
            }

            status.textContent = 'Menyimpan...';
            this.disabled = true;

            const formData = new FormData();
            formData.append('jenis', isSumberDana ? 'sumber_dana' : 'tujuan');
            formData.append('nilai', this.value);

            itemIds.forEach(function(id) {
                formData.append('item_ids[]', id);
            });

            try {
                const response = await fetch(form.dataset.autosaveUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menyimpan pilihan.');
                }

                status.textContent = 'Tersimpan: ' + result.nama;

                if (!isSumberDana) {
                    card.classList.remove('alert-outline-danger');
                    card.classList.add('alert-outline-success');
                }
            } catch (error) {
                status.textContent = error.message;
            } finally {
                this.disabled = false;
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formSimpanTransaksi');
        const tombol = document.getElementById('btnImport');

        if (!form || !tombol) return;

        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menyimpan data draft ke transaksi?')) {
                e.preventDefault();
                return;
            }

            tombol.disabled = true;
            tombol.innerHTML = 'Memproses...';
        });
    });
</script>








</body>

</html>
