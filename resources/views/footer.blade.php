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


</body>

</html>
