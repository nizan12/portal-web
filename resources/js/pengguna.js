document.addEventListener('DOMContentLoaded', function () {
    const availableServices = window.PolTree.availableServices || [];
    const triggers = Array.from(document.querySelectorAll('[data-service-card-item]'));
    const serviceCards = triggers;
    const shortcutEmptyCard = document.querySelector('[data-shortcut-empty]');
    const userNik = window.PolTree.userNik;
    const categoryStorageKey = 'poltree-service-categories-' + userNik;
    const roleStorageKey = 'poltree-service-roles-' + userNik;
    const customCategoryStorageKey = 'poltree-custom-categories-' + userNik;
    const shortcutFilterStorageKey = 'poltree-shortcut-category-filter-' + userNik;
    const bookmarkStorageKey = 'poltree-saved-services-' + userNik;
    const activeTabStorageKey = 'poltree-active-tab-' + userNik;
    const profileToggle = document.querySelector('[data-profile-toggle]');
    const profilePanel = document.querySelector('[data-profile-panel]');
    const profilePlaceholderButtons = Array.from(document.querySelectorAll('[data-profile-placeholder]'));

    const customCategoryIconsKey = 'poltree-custom-category-icons-' + userNik;
    const readCustomCategoryIcons = function() {
        try {
            return JSON.parse(window.localStorage.getItem(customCategoryIconsKey) || '{}');
        } catch(e) {
            return {};
        }
    };
    const writeCustomCategoryIcons = function(icons) {
        window.localStorage.setItem(customCategoryIconsKey, JSON.stringify(icons));
    };

    const iconPaths = {
        'home': '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>',
        'grid': '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
        'sparkles': '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
        'user': '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>',
        'chain': '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>',
        'folder': '<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>',
        'tag': '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line>',
        'book': '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>',
        'globe': '<circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>',
        'settings': '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
        'briefcase': '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
        'heart': '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>'
    };
    const modalCategoryControls = null;
    const shortcutCategoryMenu = document.querySelector('[data-shortcut-category-menu]');
    const shortcutCategoryControls = {
        toggle: document.querySelector('[data-shortcut-category-toggle]'),
        menu: shortcutCategoryMenu,
        list: shortcutCategoryMenu ? shortcutCategoryMenu.querySelector('[data-category-list]') : null,
        searchInput: shortcutCategoryMenu ? shortcutCategoryMenu.querySelector('[data-category-search-input]') : null,
        empty: shortcutCategoryMenu ? shortcutCategoryMenu.querySelector('[data-category-empty]') : null,
        addButton: shortcutCategoryMenu ? shortcutCategoryMenu.querySelector('[data-category-add]') : null,
        label: document.querySelector('[data-shortcut-category-label]'),
    };
    const categoryBuilder = {
        modal: document.querySelector('[data-category-builder-modal]'),
        titleInput: document.querySelector('[data-category-builder-title]'),
        resetButton: document.querySelector('[data-category-builder-reset]'),
        addLinkButton: document.querySelector('[data-category-builder-link-add]'),
        links: document.querySelector('[data-category-builder-links]'),
        empty: document.querySelector('[data-category-builder-empty]'),
        saveButton: document.querySelector('[data-category-builder-save]'),
    };
    const categoryBuilderState = {
        source: 'shortcut',
    };
    let activeTrigger = null;

    // Track if we are in saved fallback mode (showing all services because 0 bookmarks exist initially)
    const initialBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
    let isSavedFallbackMode = (initialBookmarks.length === 0);

    // ── Mobile Sidebar Toggle ────────────────────────────────
    const sidebar = document.querySelector('[data-sidebar]');
    const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');
    const mobileMenuToggle = document.querySelector('[data-mobile-menu-toggle]');

    if (mobileMenuToggle && sidebar) {
        mobileMenuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay && sidebar) {
        sidebarOverlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
        });
    }

    // ── Tab Switching (Semua / Tersimpan / Kategori) ──────────
    const tabSemuaBtn = document.querySelector('[data-tab-semua]');
    const tabSavedBtn = document.querySelector('[data-shortcut-saved-toggle]');
    const tabKategoriBtn = document.querySelector('[data-tab-kategori]');

    const switchTab = function (tab) {
        const viewTersimpan = document.getElementById('view-tersimpan');
        const viewKategori = document.getElementById('view-kategori');
        
        const tSemua = document.querySelector('[data-tab-semua]');
        const tSaved = document.querySelector('[data-shortcut-saved-toggle]');
        const tKategori = document.querySelector('[data-tab-kategori]');

        if (tSemua) tSemua.classList.toggle('active', tab === 'semua');
        if (tSaved) tSaved.classList.toggle('active', tab === 'tersimpan');
        if (tKategori) tKategori.classList.toggle('active', tab === 'kategori');

        if (tab === 'semua' || tab === 'tersimpan') {
            if (viewTersimpan) viewTersimpan.classList.remove('hidden');
            if (viewKategori) viewKategori.classList.add('hidden');
        } else if (tab === 'kategori') {
            if (viewTersimpan) viewTersimpan.classList.add('hidden');
            if (viewKategori) viewKategori.classList.remove('hidden');
        }

        // Save active tab to localStorage
        localStorage.setItem(activeTabStorageKey, tab);

        // Update sidebar links active class in sync with the current tab
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            if (link.getAttribute('href')) { // "Beranda" link
                link.classList.toggle('active', tab === 'tersimpan');
            } else if (link.hasAttribute('data-all-services-btn')) { // "Semua Layanan" button
                link.classList.toggle('active', tab === 'semua');
            } else if (link.hasAttribute('data-sidebar-kategori')) { // "Kategori" button
                link.classList.toggle('active', tab === 'kategori');
            }
        });

        // Apply filters based on current tab state
        updateCardVisibilities();
    };

    const initPremiumSelect = function (selectEl) {
        if (!selectEl || selectEl.dataset.premiumSelectInitialized) {
            return;
        }

        // Hide original select
        selectEl.style.display = 'none';

        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'premium-select-wrapper';
        if (selectEl.classList.contains('category-builder-link-input')) {
            wrapper.classList.add('cb-select-wrapper');
        }

        // Insert wrapper before selectEl
        selectEl.parentNode.insertBefore(wrapper, selectEl);
        wrapper.appendChild(selectEl); // move selectEl inside wrapper

        // Create trigger
        const trigger = document.createElement('div');
        trigger.className = 'premium-select-trigger';
        trigger.setAttribute('tabindex', '0');

        const triggerText = document.createElement('span');
        triggerText.className = 'trigger-text';
        
        const currentOption = selectEl.options[selectEl.selectedIndex];
        triggerText.textContent = currentOption ? currentOption.textContent : 'Pilih...';

        const triggerArrow = document.createElement('span');
        triggerArrow.className = 'trigger-arrow';
        triggerArrow.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:14px; height:14px;"><polyline points="6 9 12 15 18 9"></polyline></svg>`;

        trigger.appendChild(triggerText);
        trigger.appendChild(triggerArrow);
        wrapper.appendChild(trigger);

        // Create options container - Append to document.body to avoid clipping
        const optionsContainer = document.createElement('div');
        optionsContainer.className = 'premium-select-options';
        document.body.appendChild(optionsContainer);

        // Build option items function
        const buildOptions = function () {
            optionsContainer.innerHTML = '';
            Array.from(selectEl.options).forEach(function (opt, idx) {
                const optEl = document.createElement('div');
                optEl.className = 'premium-select-option';
                if (opt.selected) {
                    optEl.classList.add('is-selected');
                }
                optEl.dataset.value = opt.value;
                optEl.dataset.index = idx;
                
                const optText = document.createElement('span');
                optText.textContent = opt.textContent;
                
                const optCheck = document.createElement('span');
                optCheck.className = 'option-check';
                optCheck.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:12px; height:12px;"><polyline points="20 6 9 17 4 12"></polyline></svg>`;

                optEl.appendChild(optText);
                optEl.appendChild(optCheck);

                optEl.addEventListener('click', function (e) {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    triggerText.textContent = opt.textContent;
                    
                    // Trigger change event on original select
                    const event = new Event('change', { bubbles: true });
                    selectEl.dispatchEvent(event);
                    
                    closeDropdown();
                });

                optionsContainer.appendChild(optEl);
            });
        };

        const toggleDropdown = function () {
            const isOpen = optionsContainer.classList.contains('is-open');
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        };

        const openDropdown = function () {
            // Close all other open premium selects first
            document.querySelectorAll('.premium-select-options.is-open').forEach(function (el) {
                if (el !== optionsContainer) {
                    el.classList.remove('is-open');
                    const triggerEl = document.querySelector('.premium-select-trigger.is-active');
                    if (triggerEl) {
                        triggerEl.classList.remove('is-active');
                    }
                }
            });

            buildOptions(); // Rebuild options to reflect current selection/state
            
            // Position options list right under the trigger
            const rect = trigger.getBoundingClientRect();
            optionsContainer.style.position = 'fixed';
            optionsContainer.style.top = `${rect.bottom + 6}px`;
            optionsContainer.style.left = `${rect.left}px`;
            optionsContainer.style.width = `${rect.width}px`;
            optionsContainer.style.zIndex = '99999';

            optionsContainer.classList.add('is-open');
            trigger.classList.add('is-active');
        };

        const closeDropdown = function () {
            optionsContainer.classList.remove('is-open');
            trigger.classList.remove('is-active');
        };

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown();
        });

        // Focus and keyboard navigation
        trigger.addEventListener('keydown', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                toggleDropdown();
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target) && !optionsContainer.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close dropdowns on scroll or window resize to prevent floating issues
        window.addEventListener('scroll', closeDropdown, true);
        window.addEventListener('resize', closeDropdown);

        // Mark as initialized
        selectEl.dataset.premiumSelectInitialized = 'true';

        // Add reference on original select element so we can manually trigger update/refresh
        selectEl.refreshPremiumSelect = function() {
            const opt = selectEl.options[selectEl.selectedIndex];
            triggerText.textContent = opt ? opt.textContent : 'Pilih...';
        };
    };
    window.initPremiumSelect = initPremiumSelect;

    if (tabSemuaBtn) {
        tabSemuaBtn.addEventListener('click', function () {
            switchTab('semua');
        });
    }

    if (tabSavedBtn) {
        tabSavedBtn.addEventListener('click', function () {
            switchTab('tersimpan');
        });
    }

    if (tabKategoriBtn) {
        tabKategoriBtn.addEventListener('click', function () {
            switchTab('kategori');
        });
    }

    const sidebarKategoriBtn = document.querySelector('[data-sidebar-kategori]');
    if (sidebarKategoriBtn) {
        sidebarKategoriBtn.addEventListener('click', function (e) {
            e.preventDefault();
            switchTab('kategori');
            if (sidebar) sidebar.classList.remove('open');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        });
    }

    const allServicesBtn = document.querySelector('[data-all-services-btn]');
    if (allServicesBtn) {
        allServicesBtn.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Clear category filter
            applyShortcutFilter('');
            
            // Switch to semua tab
            switchTab('semua');
            
            if (sidebar) sidebar.classList.remove('open');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
        });
    }

    const readStoredCategories = function () {
        try {
            return JSON.parse(window.localStorage.getItem(categoryStorageKey) || '{}');
        } catch (error) {
            return {};
        }
    };

    const writeStoredCategories = function (items) {
        window.localStorage.setItem(categoryStorageKey, JSON.stringify(items));
    };

    const readCustomCategories = function () {
        try {
            return JSON.parse(window.localStorage.getItem(customCategoryStorageKey) || '[]');
        } catch (error) {
            return [];
        }
    };

    const writeStoredCategories_placeholder_not_used = null;

    const writeCustomCategories = function (items) {
        window.localStorage.setItem(customCategoryStorageKey, JSON.stringify(items));
    };

    const readShortcutFilter = function () {
        return (window.localStorage.getItem(shortcutFilterStorageKey) || '').trim();
    };

    const writeShortcutFilter = function (value) {
        const normalizedFilter = normalizeCategoryName(value);

        if (normalizedFilter) {
            window.localStorage.setItem(shortcutFilterStorageKey, normalizedFilter);
            return;
        }

        window.localStorage.removeItem(shortcutFilterStorageKey);
    };

    const getServiceKey = function (element) {
        if (!element) return '';
        return ((element.dataset.title || '') + (element.dataset.url || '')).trim();
    };

    const updateCardVisibilities = function () {
        const currentFilter = readShortcutFilter();
        const tSaved = document.querySelector('[data-shortcut-saved-toggle]');
        const storedBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
        const isFilteringSaved = (tSaved ? tSaved.classList.contains('active') : false) && !currentFilter && !isSavedFallbackMode && storedBookmarks.length > 0;
        const activeRole = window.PolTree.activeRole;

        const storedRoles = JSON.parse(localStorage.getItem(roleStorageKey) || '{}');
        const cards = document.querySelectorAll('.service-card');

        let visibleCount = 0;

        cards.forEach(card => {
            const key = getServiceKey(card);

            // 1. Bookmark Match
            const bookmarkMatches = !isFilteringSaved || storedBookmarks.includes(key);

            // 2. Category Match
            const resolvedCategory = getResolvedCategoryForTrigger(card);
            const categoryMatches = !currentFilter || resolvedCategory.toLowerCase() === currentFilter.toLowerCase();

            // 3. Role Match
            const roleName = storedRoles[key];
            const roleMatches = !activeRole || !roleName || roleName === activeRole;

            const shouldShow = bookmarkMatches && categoryMatches && roleMatches;

            if (shouldShow) {
                card.style.display = 'flex';
                card.hidden = false;
                visibleCount++;
            } else {
                card.style.display = 'none';
                card.hidden = true;
            }
        });

        // ── Manage Section Headings and Layout Balance ────────
        const officialSection = document.querySelector('[data-section="official"]');
        const personalSection = document.querySelector('[data-section="personal"]');
        
        let officialVisible = 0;
        let personalVisible = 0;

        if (officialSection) {
            const officialCards = officialSection.querySelectorAll('.service-card');
            const totalOfficialCards = officialCards.length;
            
            officialCards.forEach(card => {
                if (!card.hidden && card.style.display !== 'none') {
                    officialVisible++;
                }
            });
            if (totalOfficialCards > 0 && officialVisible === 0) {
                officialSection.style.display = 'none';
            } else {
                officialSection.style.display = 'block';
            }
        }

        if (personalSection) {
            const personalCards = personalSection.querySelectorAll('.service-card');
            const totalPersonalCards = personalCards.length;
            
            personalCards.forEach(card => {
                if (!card.hidden && card.style.display !== 'none') {
                    personalVisible++;
                }
            });
            if (totalPersonalCards > 0 && personalVisible === 0) {
                personalSection.style.display = 'none';
            } else {
                personalSection.style.display = 'block';
            }
        }

        // Show/hide empty state
        const emptyStateCard = document.getElementById('empty-state-card');
        if (emptyStateCard) {
            if (visibleCount === 0) {
                emptyStateCard.style.display = 'flex';
                if (isFilteringSaved) {
                    emptyStateCard.querySelector('.empty-state-title').textContent = 'Belum Ada Layanan Tersimpan';
                    emptyStateCard.querySelector('.empty-state-desc').textContent = 'Klik ikon bookmark pada layanan untuk menambahkannya ke sini.';
                } else if (currentFilter) {
                    emptyStateCard.querySelector('.empty-state-title').textContent = 'Tidak Ada Layanan Ditemukan';
                    emptyStateCard.querySelector('.empty-state-desc').textContent = `Tidak ada layanan di bawah kategori "${currentFilter}".`;
                } else {
                    emptyStateCard.querySelector('.empty-state-title').textContent = 'Tidak Ada Layanan Ditemukan';
                    emptyStateCard.querySelector('.empty-state-desc').textContent = 'Silakan periksa kembali filter Anda.';
                }
            } else {
                emptyStateCard.style.display = 'none';
            }
        }

        // Show/hide active category indicator bar
        const filterIndicator = document.getElementById('category-filter-indicator');
        if (filterIndicator) {
            if (currentFilter) {
                filterIndicator.style.display = 'flex';
            } else {
                filterIndicator.style.display = 'none';
            }
        }
    };

    const syncServiceStates = function () {
        const storedBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
        const storedCategories = readStoredCategories();
        const storedRoles = JSON.parse(localStorage.getItem(roleStorageKey) || '{}');
        const cards = document.querySelectorAll('.service-card');

        cards.forEach(card => {
            const key = getServiceKey(card);

            // Sync Bookmark
            const bookmarkBtn = card.querySelector('[data-service-bookmark-toggle]');
            if (bookmarkBtn && storedBookmarks.includes(key)) {
                bookmarkBtn.classList.add('is-saved');
            }

            // Sync Category selection in dropdown
            const categoryName = storedCategories[key];
            if (categoryName) {
                const option = card.querySelector(`[data-card-category-option="${categoryName}"]`);
                if (option) {
                    card.querySelectorAll('[data-card-category-option]').forEach(opt => opt.classList.remove('is-selected'));
                    option.classList.add('is-selected');
                }
            }

            // Sync Role selection in dropdown
            const roleName = storedRoles[key];
            if (roleName) {
                const option = card.querySelector(`[data-card-role-option="${roleName}"]`);
                if (option) {
                    card.querySelectorAll('[data-card-role-option]').forEach(opt => opt.classList.remove('is-selected'));
                    option.classList.add('is-selected');
                }
            }
        });

        // Trigger unified visibility calculations
        updateCardVisibilities();
    };

    const normalizeCategoryName = function (value) {
        return (value || '').replace(/\s+/g, ' ').trim();
    };

    const updateBodyLock = function () {
        const hasOpenCategoryBuilder = categoryBuilder.modal ? categoryBuilder.modal.classList.contains('flex') : false;
        const hasOpenLinkModal = document.getElementById('linkModal')?.classList.contains('flex');
        const hasOpenPasswordModal = document.getElementById('passwordModal')?.classList.contains('flex');
        const hasOpenProfileModal = document.getElementById('profileModal')?.classList.contains('flex');

        document.body.classList.toggle('modal-open', hasOpenCategoryBuilder || hasOpenLinkModal || hasOpenPasswordModal || hasOpenProfileModal);
    };

    const findTriggerByTitle = function (serviceTitle) {
        const normalizedTitle = normalizeCategoryName(serviceTitle).toLowerCase();

        if (!normalizedTitle) {
            return null;
        }

        return triggers.find(function (trigger) {
            return normalizeCategoryName(trigger.dataset.title || '').toLowerCase() === normalizedTitle;
        }) || null;
    };

    const getCategoryOptions = function (list) {
        return list ? Array.from(list.querySelectorAll('[data-category-option]')) : [];
    };

    const categoryExists = function (list, categoryName) {
        const normalizedCategory = normalizeCategoryName(categoryName).toLowerCase();

        return getCategoryOptions(list).some(function (option) {
            return normalizeCategoryName(option.dataset.categoryOption).toLowerCase() === normalizedCategory;
        });
    };

    const getCategoryIconMarkup = function (categoryName) {
        const lowerCategory = normalizeCategoryName(categoryName).toLowerCase();

        if (lowerCategory.includes('akadem')) {
            return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6.5A2.5 2.5 0 0 1 6.5 4H11v15H6.5A2.5 2.5 0 0 0 4 21V6.5ZM20 6.5A2.5 2.5 0 0 0 17.5 4H13v15h4.5A2.5 2.5 0 0 1 20 21V6.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" /></svg>';
        }

        if (lowerCategory.includes('umum')) {
            return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Zm0 0c2.2 2.2 3.5 5.4 3.5 9S14.2 18.8 12 21m0-18C9.8 5.2 8.5 8.4 8.5 12S9.8 18.8 12 21m-8-9h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>';
        }

        return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H10l2 2h5.5A2.5 2.5 0 0 1 20 9.5v7a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" /></svg>';
    };

    const buildCategoryOption = function (categoryName) {
        const option = document.createElement('button');

        option.type = 'button';
        option.className = 'service-category-option';
        option.dataset.categoryOption = categoryName;
        option.innerHTML =
        '<span class="service-category-option-icon" aria-hidden="true">' + getCategoryIconMarkup(categoryName) + '</span>' +
        '<span class="service-category-option-label"></span>' +
        '<span class="service-category-option-check" aria-hidden="true"></span>';
        option.querySelector('.service-category-option-label').textContent = categoryName;

        return option;
    };

    const ensureCategoryOption = function (list, categoryName) {
        const normalizedCategory = normalizeCategoryName(categoryName);

        if (!list || !normalizedCategory || categoryExists(list, normalizedCategory)) {
            return;
        }

        list.appendChild(buildCategoryOption(normalizedCategory));
    };

    const syncCustomCategories = function () {
        const customCategories = readCustomCategories();
        customCategories.forEach(function (categoryName) {
            ensureCategoryOption(modalCategoryControls?.list, categoryName);
            ensureCategoryOption(shortcutCategoryControls.list, categoryName);

            document.querySelectorAll('.card-dropdown-list').forEach(list => {
                ensureCardCategoryOption(list, categoryName);
            });
        });

        // Dynamically render folder cards in view-kategori grid for custom categories
        // Remove existing custom folder elements to prevent duplication
        document.querySelectorAll('.folder-card.custom-folder').forEach(el => el.remove());
        
        const folderGrid = document.querySelector('.folder-grid');
        const dashedFolder = document.querySelector('.folder-card.dashed-folder');
        
        if (folderGrid && dashedFolder) {
            customCategories.forEach(function (categoryName) {
                // Find all cards matching this custom category
                const matchedCards = serviceCards.filter(function (card) {
                    return getResolvedCategoryForTrigger(card).toLowerCase() === categoryName.toLowerCase();
                });
                
                const totalLinks = matchedCards.length;
                
                // Get the saved custom category icon
                const customCategoryIcons = readCustomCategoryIcons();
                const iconValue = customCategoryIcons[categoryName] || '';
                
                let headerInnerHtml = '';
                if (iconValue && iconPaths[iconValue]) {
                    headerInnerHtml = `
                        <div class="category-folder-icon-wrapper" style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 12px; background: rgba(8, 13, 95, 0.04); color: #080d5f; transition: all 0.2s;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                ${iconPaths[iconValue]}
                            </svg>
                        </div>
                    `;
                } else {
                    headerInnerHtml = `
                        <div class="category-folder-icon-wrapper" style="display: flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 12px; background: rgba(8, 13, 95, 0.04); color: #080d5f; transition: all 0.2s;">
                            <img src="/images/logo-polibatam.png" alt="Logo" style="width: 32px; height: 32px; object-fit: contain;">
                        </div>
                    `;
                }
                
                const folderCard = document.createElement('div');
                folderCard.className = 'folder-card custom-folder';
                folderCard.setAttribute('data-category-folder', categoryName);
                folderCard.innerHTML = `
                    <button type="button" class="folder-edit-btn" data-category-edit-toggle="${categoryName}" aria-label="Edit Kategori">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"></path>
                        </svg>
                    </button>
                    <div class="folder-header">
                        ${headerInnerHtml}
                    </div>
                    <div class="folder-body">
                        <h3 class="folder-title">${categoryName}</h3>
                        <p class="folder-count">${totalLinks} Layanan</p>
                    </div>
                `;
                
                folderGrid.insertBefore(folderCard, dashedFolder);
            });
        }
    };

    const ensureCardCategoryOption = function (list, categoryName) {
        if (!list || !categoryName) return;

        const existing = list.querySelector(`[data-card-category-option="${categoryName}"]`);
        if (existing) return;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'card-dropdown-item';
        button.setAttribute('data-card-category-option', categoryName);

        button.innerHTML = `
        <div class="card-dropdown-item-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5H10l2 2h5.5A2.5 2.5 0 0 1 20 9.5v7a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z" />
            </svg>
        </div>
        <span class="card-dropdown-item-label">${categoryName}</span>
        <div class="card-dropdown-item-circle"></div>
        `;

        list.appendChild(button);
    };

    const updateCategoryFilter = function (controls) {
        if (!controls?.list) {
            return;
        }

        const keyword = normalizeCategoryName(controls.searchInput?.value || '').toLowerCase();
        let visibleCount = 0;

        getCategoryOptions(controls.list).forEach(function (option) {
            const matches = normalizeCategoryName(option.dataset.categoryOption).toLowerCase().includes(keyword);

            option.hidden = !matches;

            if (matches) {
                visibleCount += 1;
            }
        });

        if (controls.empty) {
            controls.empty.classList.toggle('is-visible', visibleCount === 0);
        }
    };

    const setActiveCategoryOption = function (controls, categoryName) {
        getCategoryOptions(controls?.list).forEach(function (option) {
            option.classList.toggle('is-active', option.dataset.categoryOption === categoryName);
        });
    };

    const getResolvedCategoryForTrigger = function (trigger) {
        const storedCategories = readStoredCategories();
        return normalizeCategoryName(storedCategories[getServiceKey(trigger)] || trigger?.dataset.category || 'Layanan');
    };

    const applyShortcutFilter = function (categoryName) {
        const currentFilter = normalizeCategoryName(categoryName);

        writeShortcutFilter(currentFilter);

        if (shortcutCategoryControls.label) {
            shortcutCategoryControls.label.textContent = currentFilter || 'Semua Layanan';
        }

        setActiveCategoryOption(shortcutCategoryControls, currentFilter);

        // Call the unified visibilities function
        updateCardVisibilities();
    };

    const updateCategoryBuilderEmptyState = function () {
        if (!categoryBuilder.empty || !categoryBuilder.links) {
            return;
        }

        categoryBuilder.empty.hidden = categoryBuilder.links.children.length !== 0;
    };

    const buildCategoryLinkRow = function (value) {
        const row = document.createElement('div');
        row.className = 'category-builder-link-row';

        let optionsHtml = '<option value="">Pilih layanan...</option>';
        availableServices.forEach(service => {
            const selected = service === value ? 'selected' : '';
            optionsHtml += `<option value="${service}" ${selected}>${service}</option>`;
        });

        row.innerHTML = `
        <select class="category-builder-link-input" data-category-builder-link-input style="appearance: none; -webkit-appearance: none;">
            ${optionsHtml}
        </select>
        <button type="button" class="category-builder-link-remove" data-category-builder-link-remove aria-label="Hapus link">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg>
        </button>
        `;

        return row;
    };

    const resetCategoryBuilderForm = function () {
        if (categoryBuilder.titleInput) {
            categoryBuilder.titleInput.value = '';
        }

        if (categoryBuilder.links) {
            categoryBuilder.links.innerHTML = '';
        }

        updateCategoryBuilderEmptyState();
    };

    const closeCategoryBuilder = function () {
        if (!categoryBuilder.modal || categoryBuilder.modal.classList.contains('hidden')) {
            return;
        }
        categoryBuilder.modal.classList.add('closing');
        setTimeout(function() {
            categoryBuilder.modal.classList.add('hidden');
            categoryBuilder.modal.classList.remove('flex', 'closing');
            // Hide delete section on close
            const deleteSection = categoryBuilder.modal.querySelector('[data-category-builder-delete-section]');
            if (deleteSection) deleteSection.style.display = 'none';
            updateBodyLock();
        }, 300);
    };

    window.selectUserBuilderIcon = function (radio) {
        document.querySelectorAll('.cb-icon-option').forEach(function(lbl) {
            lbl.classList.remove('is-active');
        });
        radio.parentElement.classList.add('is-active');
    };

    const selectUserBuilderIconByValue = function (val) {
        const radio = document.querySelector(`input[name="builder_icon"][value="${val || ''}"]`);
        if (radio) {
            radio.checked = true;
            window.selectUserBuilderIcon(radio);
        }
    };

    const openCategoryBuilderForEdit = function (categoryName) {
        if (!categoryBuilder.modal) {
            return;
        }

        categoryBuilderState.source = 'edit';
        categoryBuilderState.editCategoryName = categoryName;
        categoryBuilderState.editCategoryDbId = null; // Will be set if DB-backed

        closeCategoryMenu(modalCategoryControls);
        closeCategoryMenu(shortcutCategoryControls);
        closeProfilePanel();
        resetCategoryBuilderForm();

        // Prefill title input
        if (categoryBuilder.titleInput) {
            categoryBuilder.titleInput.value = categoryName;
        }

        // Prefill icon radio
        const customCategoryIcons = readCustomCategoryIcons();
        const iconValue = customCategoryIcons[categoryName] || '';
        selectUserBuilderIconByValue(iconValue);

        // Find all cards matching this category
        const matchedCards = serviceCards.filter(function (card) {
            return getResolvedCategoryForTrigger(card).toLowerCase() === categoryName.toLowerCase();
        });

        // Add preselected rows
        matchedCards.forEach(function (card) {
            const title = card.dataset.title || '';
            const row = buildCategoryLinkRow(title);
            if (categoryBuilder.links) {
                categoryBuilder.links.appendChild(row);
                const input = row.querySelector('[data-category-builder-link-input]');
                if (input) {
                    initPremiumSelect(input);
                }
            }
        });

        updateCategoryBuilderEmptyState();

        // Update modal title for edit mode
        const modalTitle = categoryBuilder.modal.querySelector('[data-category-builder-modal-title]');
        if (modalTitle) modalTitle.textContent = 'Edit Kategori';

        // Show delete section in edit mode
        const deleteSection = categoryBuilder.modal.querySelector('[data-category-builder-delete-section]');
        if (deleteSection) deleteSection.style.display = 'block';

        categoryBuilder.modal.classList.remove('hidden');
        categoryBuilder.modal.classList.add('flex');
        updateBodyLock();

        if (categoryBuilder.titleInput) {
            window.requestAnimationFrame(function () {
                categoryBuilder.titleInput.focus();
            });
        }
    };

    const openCategoryBuilder = function (source) {
        if (!categoryBuilder.modal) {
            return;
        }

        categoryBuilderState.source = source || 'shortcut';
        closeCategoryMenu(modalCategoryControls);
        closeCategoryMenu(shortcutCategoryControls);
        closeProfilePanel();
        resetCategoryBuilderForm();

        // Reset icon selection to default (empty value)
        selectUserBuilderIconByValue('');

        // Update modal title for create mode
        const modalTitle = categoryBuilder.modal.querySelector('[data-category-builder-modal-title]');
        if (modalTitle) modalTitle.textContent = 'Tambah Kategori';

        // Hide delete section in create mode
        const deleteSection = categoryBuilder.modal.querySelector('[data-category-builder-delete-section]');
        if (deleteSection) deleteSection.style.display = 'none';

        categoryBuilder.modal.classList.remove('hidden');
        categoryBuilder.modal.classList.add('flex');
        updateBodyLock();

        if (categoryBuilder.titleInput) {
            window.requestAnimationFrame(function () {
                categoryBuilder.titleInput.focus();
            });
        }
    };

    const getCategoryBuilderLinks = function () {
        if (!categoryBuilder.links) {
            return [];
        }

        return Array.from(categoryBuilder.links.querySelectorAll('[data-category-builder-link-input]'))
            .map(function (input) {
                return normalizeCategoryName(input.value);
            })
            .filter(Boolean);
    };

    const closeCategoryMenu = function (controls) {
        if (!controls?.toggle || !controls.menu) {
            return;
        }

        controls.menu.hidden = true;
        controls.toggle.setAttribute('aria-expanded', 'false');

        if (controls.searchInput) {
            controls.searchInput.value = '';
        }

        updateCategoryFilter(controls);
    };

    const openCategoryMenu = function (controls) {
        if (!controls?.toggle || !controls.menu) {
            return;
        }

        if (controls !== modalCategoryControls) {
            closeCategoryMenu(modalCategoryControls);
        }

        if (controls !== shortcutCategoryControls) {
            closeCategoryMenu(shortcutCategoryControls);
        }

        closeProfilePanel();
        closeCategoryBuilder();
        syncCustomCategories();
        controls.menu.hidden = false;
        controls.toggle.setAttribute('aria-expanded', 'true');
        updateCategoryFilter(controls);

        if (controls.searchInput) {
            window.requestAnimationFrame(function () {
                controls.searchInput.focus();
            });
        }
    };

    const toggleCategoryMenu = function (controls) {
        if (!controls?.toggle || !controls.menu) {
            return;
        }

        const isOpen = controls.toggle.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            closeCategoryMenu(controls);
            return;
        }

        openCategoryMenu(controls);
    };

    function closeProfilePanel() {
        if (!profileToggle || !profilePanel || profilePanel.hidden) {
            return;
        }

        profilePanel.hidden = true;
        profileToggle.setAttribute('aria-expanded', 'false');
    }

    const openProfilePanel = function () {
        if (!profileToggle || !profilePanel) {
            return;
        }

        closeCategoryMenu(modalCategoryControls);
        closeCategoryMenu(shortcutCategoryControls);
        closeCategoryBuilder();
        profilePanel.hidden = false;
        profileToggle.setAttribute('aria-expanded', 'true');
    };

    const toggleProfilePanel = function () {
        if (!profileToggle || !profilePanel) {
            return;
        }

        if (profilePanel.hidden) {
            openProfilePanel();
            return;
        }

        closeProfilePanel();
    };

    const persistCustomCategory = function (categoryName) {
        const normalizedCategory = normalizeCategoryName(categoryName);

        if (!normalizedCategory) {
            return '';
        }

        if (!categoryExists(modalCategoryControls?.list, normalizedCategory) && !categoryExists(shortcutCategoryControls.list, normalizedCategory)) {
            const storedCustomCategories = readCustomCategories();

            storedCustomCategories.push(normalizedCategory);
            writeCustomCategories(Array.from(new Set(storedCustomCategories)));
        }

        ensureCategoryOption(modalCategoryControls?.list, normalizedCategory);
        ensureCategoryOption(shortcutCategoryControls.list, normalizedCategory);
        syncCustomCategories();

        return normalizedCategory;
    };

    const saveCategoryBuilder = function () {
        const newCategoryName = normalizeCategoryName(categoryBuilder.titleInput?.value || '');

        if (!newCategoryName) {
            if (categoryBuilder.titleInput) {
                categoryBuilder.titleInput.focus();
            }
            return;
        }

        const selectedLinkTitles = getCategoryBuilderLinks();

        // Save selected icon
        const selectedIconRadio = document.querySelector('input[name="builder_icon"]:checked');
        const iconValue = selectedIconRadio ? selectedIconRadio.value : '';
        const customCategoryIcons = readCustomCategoryIcons();
        if (iconValue) {
            customCategoryIcons[newCategoryName] = iconValue;
        } else {
            delete customCategoryIcons[newCategoryName];
        }

        if (categoryBuilderState.source === 'edit') {
            const originalName = categoryBuilderState.editCategoryName;

            // For DB-backed categories, call the server API
            if (categoryBuilderState.editCategoryDbId) {
                const dbId = categoryBuilderState.editCategoryDbId;
                const updateUrl = (window.PolTree.updateCategoryRoute || '').replace('__ID__', dbId);
                const csrfToken = window.PolTree.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

                fetch(updateUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nama_kategori: newCategoryName,
                        icon: iconValue
                    })
                })
                .then(function(response) {
                    if (!response.ok) {
                        return response.json().then(function(errData) {
                            throw new Error(errData.message || 'Gagal memperbarui kategori.');
                        }).catch(function() {
                            throw new Error('Gagal memproses respons server.');
                        });
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        showToast(data.message || 'Kategori berhasil diperbarui!', 'success');
                        // Reload page to reflect changes from server
                        window.location.reload();
                    } else {
                        showToast(data.message || 'Gagal memperbarui kategori.', 'error');
                    }
                })
                .catch(function(error) {
                    console.error('Error updating category:', error);
                    showToast(error.message || 'Terjadi kesalahan saat memperbarui kategori.', 'error');
                });

                closeCategoryBuilder();
                return;
            }
            
            
            // 1. Persist the new category name
            persistCustomCategory(newCategoryName);

            // 2. If renamed, remove originalName from customCategories and icons
            if (originalName.toLowerCase() !== newCategoryName.toLowerCase()) {
                const storedCustomCategories = readCustomCategories();
                const filtered = storedCustomCategories.filter(c => c.toLowerCase() !== originalName.toLowerCase());
                writeCustomCategories(Array.from(new Set(filtered)));

                delete customCategoryIcons[originalName];
            }

            // 3. Update all card mappings in storedCategories
            const storedCategories = readStoredCategories();
            
            // Clear original category association first
            const previouslyAssociatedCards = serviceCards.filter(function (card) {
                return getResolvedCategoryForTrigger(card).toLowerCase() === originalName.toLowerCase();
            });

            previouslyAssociatedCards.forEach(function (card) {
                const key = getServiceKey(card);
                if ((card.dataset.category || '').toLowerCase() === originalName.toLowerCase()) {
                    // Explicitly map to 'Layanan' to prevent falling back to the old category name
                    storedCategories[key] = 'Layanan';
                } else {
                    delete storedCategories[key];
                }
            });

            // Map all currently selected services to the new category name
            selectedLinkTitles.forEach(function (linkTitle) {
                const matchedTrigger = findTriggerByTitle(linkTitle);
                if (matchedTrigger) {
                    storedCategories[getServiceKey(matchedTrigger)] = newCategoryName;
                }
            });

            writeStoredCategories(storedCategories);

            // 4. Update filters
            const currentFilter = readShortcutFilter();
            if (currentFilter.toLowerCase() === originalName.toLowerCase()) {
                applyShortcutFilter(newCategoryName);
            } else {
                applyShortcutFilter(currentFilter);
            }
        } else {
            // Standard category save logic
            const persistedName = persistCustomCategory(newCategoryName);
            const targetTriggers = [];

            if (categoryBuilderState.source === 'service' && activeTrigger) {
                targetTriggers.push(activeTrigger);
            }

            selectedLinkTitles.forEach(function (linkTitle) {
                const matchedTrigger = findTriggerByTitle(linkTitle);
                if (matchedTrigger) {
                    targetTriggers.push(matchedTrigger);
                }
            });

            if (targetTriggers.length) {
                const storedCategories = readStoredCategories();
                targetTriggers.forEach(function (trigger) {
                    storedCategories[getServiceKey(trigger)] = persistedName;
                });
                writeStoredCategories(storedCategories);
            }

            applyShortcutFilter(categoryBuilderState.source === 'shortcut' ? persistedName : readShortcutFilter());
        }

        writeCustomCategoryIcons(customCategoryIcons);

        syncCustomCategories();
        syncServiceStates();
        closeCategoryBuilder();

        if (typeof showToast === 'function') {
            if (categoryBuilderState.source === 'edit') {
                showToast(`Kategori "${newCategoryName}" berhasil diperbarui!`, 'success');
            } else {
                showToast(`Kategori "${newCategoryName}" berhasil dibuat!`, 'success');
            }
        }
    };

    if (shortcutCategoryControls.toggle) {
        shortcutCategoryControls.toggle.addEventListener('click', function (event) {
            event.preventDefault();
            toggleCategoryMenu(shortcutCategoryControls);
        });
    }

    // Handle clearing category filter
    const clearCategoryBtn = document.getElementById('clear-category-filter-btn');
    if (clearCategoryBtn) {
        clearCategoryBtn.addEventListener('click', function (e) {
            e.preventDefault();
            applyShortcutFilter('');
        });
    }

    if (shortcutCategoryControls.searchInput) {
        shortcutCategoryControls.searchInput.addEventListener('input', function () {
            updateCategoryFilter(shortcutCategoryControls);
        });
    }

    if (shortcutCategoryControls.list) {
        shortcutCategoryControls.list.addEventListener('click', function (event) {
            const option = event.target.closest('[data-category-option]');

            if (!option) {
                return;
            }

            const selectedCategory = normalizeCategoryName(option.dataset.categoryOption);
            const currentFilter = normalizeCategoryName(readShortcutFilter());

            applyShortcutFilter(currentFilter.toLowerCase() === selectedCategory.toLowerCase() ? '' : selectedCategory);
            closeCategoryMenu(shortcutCategoryControls);
        });
    }

    if (shortcutCategoryControls.addButton) {
        shortcutCategoryControls.addButton.addEventListener('click', function () {
            openCategoryBuilder('shortcut');
        });
    }

    if (categoryBuilder.addLinkButton) {
        categoryBuilder.addLinkButton.addEventListener('click', function () {
            if (!categoryBuilder.links) {
                return;
            }

            const row = buildCategoryLinkRow('');

            categoryBuilder.links.appendChild(row);

            const input = row.querySelector('[data-category-builder-link-input]');
            if (input) {
                initPremiumSelect(input);
            }

            updateCategoryBuilderEmptyState();

            const trigger = row.querySelector('.premium-select-trigger');
            if (trigger) {
                trigger.focus();
            }
        });
    }

    if (categoryBuilder.resetButton) {
        categoryBuilder.resetButton.addEventListener('click', function () {
            if (categoryBuilderState.source === 'edit') {
                const categoryToDelete = categoryBuilderState.editCategoryName;
                if (!categoryToDelete) return;
                
                if (confirm(`Apakah Anda yakin ingin menghapus kategori "${categoryToDelete}" beserta seluruh pengelompokannya?`)) {
                    // Check if this is a DB-backed category
                    if (categoryBuilderState.editCategoryDbId) {
                        const dbId = categoryBuilderState.editCategoryDbId;
                        const deleteUrl = (window.PolTree.deleteCategoryRoute || '').replace('__ID__', dbId);
                        const csrfToken = window.PolTree.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';

                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .then(function(response) {
                            if (!response.ok) {
                                return response.json().then(function(errData) {
                                    throw new Error(errData.message || 'Gagal menghapus kategori.');
                                }).catch(function() {
                                    throw new Error('Gagal memproses respons server.');
                                });
                            }
                            return response.json();
                        })
                        .then(function(data) {
                            if (data.success) {
                                showToast(data.message || 'Kategori berhasil dihapus.', 'success');
                                // Reload page to reflect changes from server
                                window.location.reload();
                            } else {
                                showToast(data.message || 'Gagal menghapus kategori.', 'error');
                            }
                        })
                        .catch(function(error) {
                            console.error('Error deleting category:', error);
                            showToast(error.message || 'Terjadi kesalahan saat menghapus kategori.', 'error');
                        });

                        closeCategoryBuilder();
                        return;
                    }

                    // localStorage-only category: original behavior
                    // Remove from customCategories list
                    const storedCustomCategories = readCustomCategories();
                    const filtered = storedCustomCategories.filter(c => c.toLowerCase() !== categoryToDelete.toLowerCase());
                    writeCustomCategories(filtered);
                    
                    // Remove icon if exists
                    const customCategoryIcons = readCustomCategoryIcons();
                    delete customCategoryIcons[categoryToDelete];
                    writeCustomCategoryIcons(customCategoryIcons);

                    // Clear associations
                    const storedCategories = readStoredCategories();
                    serviceCards.forEach(function (card) {
                        const key = getServiceKey(card);
                        const resolved = getResolvedCategoryForTrigger(card);
                        if (resolved.toLowerCase() === categoryToDelete.toLowerCase()) {
                            if ((card.dataset.category || '').toLowerCase() === categoryToDelete.toLowerCase()) {
                                storedCategories[key] = 'Layanan';
                            } else {
                                delete storedCategories[key];
                            }
                        }
                    });
                    writeStoredCategories(storedCategories);
                    
                    // If current filter was this category, clear the filter
                    const currentFilter = readShortcutFilter();
                    if (currentFilter.toLowerCase() === categoryToDelete.toLowerCase()) {
                        applyShortcutFilter('');
                    }
                    
                    syncCustomCategories();
                    syncServiceStates();
                    closeCategoryBuilder();
                    
                    if (typeof showToast === 'function') {
                        showToast(`Kategori "${categoryToDelete}" berhasil dihapus.`, 'info');
                    }
                }
            } else {
                resetCategoryBuilderForm();

                if (categoryBuilder.titleInput) {
                    categoryBuilder.titleInput.focus();
                }
            }
        });
    }

    if (categoryBuilder.links) {
        categoryBuilder.links.addEventListener('click', function (event) {
            const removeButton = event.target.closest('[data-category-builder-link-remove]');

            if (!removeButton) {
                return;
            }

            const row = removeButton.closest('.category-builder-link-row');

            if (row) {
                row.remove();
                updateCategoryBuilderEmptyState();
            }
        });
    }

    if (categoryBuilder.saveButton) {
        categoryBuilder.saveButton.addEventListener('click', function () {
            saveCategoryBuilder();
        });
    }

    if (profileToggle) {
        profileToggle.addEventListener('click', function (event) {
            event.preventDefault();
            toggleProfilePanel();
        });
    }

    profilePlaceholderButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            closeProfilePanel();
        });
    });

    if (categoryBuilder.modal) {
        categoryBuilder.modal.addEventListener('click', function (event) {
            if (event.target === categoryBuilder.modal) {
                closeCategoryBuilder();
            }
            // Handle close button clicks (X and Batal)
            if (event.target.closest('[data-category-builder-close]')) {
                closeCategoryBuilder();
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (profilePanel && !profilePanel.hidden && !event.target.closest('.profile-menu-wrap')) {
            closeProfilePanel();
        }

        if (modalCategoryControls?.menu && !modalCategoryControls.menu.hidden && !event.target.closest('.service-modal-action-group')) {
            closeCategoryMenu(modalCategoryControls);
        }

        if (shortcutCategoryControls.menu && !shortcutCategoryControls.menu.hidden && !event.target.closest('.sidebar-category-wrap')) {
            closeCategoryMenu(shortcutCategoryControls);
        }

        // Card dropdown toggle
        const dropdownToggle = event.target.closest('[data-card-dropdown-toggle]');
        if (dropdownToggle) {
            const card = dropdownToggle.closest('.service-card');
            const menu = dropdownToggle.nextElementSibling;
            const isActive = menu.classList.contains('active');

            document.querySelectorAll('[data-card-dropdown-menu]').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.service-card').forEach(el => el.classList.remove('is-active'));

            if (!isActive) {
                menu.classList.add('active');
                card.classList.add('is-active');
            }
        } else if (!event.target.closest('[data-card-dropdown-menu]')) {
            document.querySelectorAll('[data-card-dropdown-menu]').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.service-card').forEach(el => el.classList.remove('is-active'));
        }

        // Handle category assignment from card dropdown
        const categoryOption = event.target.closest('[data-card-category-option]');
        if (categoryOption) {
            const card = categoryOption.closest('.service-card');
            const categoryName = categoryOption.getAttribute('data-card-category-option');

            const key = getServiceKey(card);
            const storedCategories = readStoredCategories();
            storedCategories[key] = categoryName;
            writeStoredCategories(storedCategories);

            card.querySelectorAll('[data-card-category-option]').forEach(opt => opt.classList.remove('is-selected'));
            categoryOption.classList.add('is-selected');

            categoryOption.closest('[data-card-dropdown-menu]').classList.remove('active');
            card.classList.remove('is-active');

            syncCustomCategories();
            syncServiceStates();
            applyShortcutFilter(readShortcutFilter());

            if (typeof showToast === 'function') {
                showToast(`Layanan berhasil dipindahkan ke Kategori "${categoryName}"!`, 'success');
            }
        }

        // Handle role assignment from card dropdown
        const roleOption = event.target.closest('[data-card-role-option]');
        if (roleOption) {
            const card = roleOption.closest('.service-card');
            const roleName = roleOption.getAttribute('data-card-role-option');

            const key = getServiceKey(card);
            const storedRoles = JSON.parse(localStorage.getItem(roleStorageKey) || '{}');
            storedRoles[key] = roleName;
            localStorage.setItem(roleStorageKey, JSON.stringify(storedRoles));

            card.querySelectorAll('[data-card-role-option]').forEach(opt => opt.classList.remove('is-selected'));
            roleOption.classList.add('is-selected');

            roleOption.closest('[data-card-dropdown-menu]').classList.remove('active');
            card.classList.remove('is-active');

            syncServiceStates();

            if (typeof showToast === 'function') {
                showToast(`Akses Peran layanan berhasil diatur ke "${roleName}"!`, 'success');
            }
        }

        // Handle bookmark toggle
        const bookmarkBtn = event.target.closest('[data-service-bookmark-toggle]');
        if (bookmarkBtn) {
            const card = bookmarkBtn.closest('.service-card');
            const key = getServiceKey(card);
            const title = card.dataset.title || 'Layanan';

            const storedBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
            const index = storedBookmarks.indexOf(key);

            let newlySaved = false;
            if (index === -1) {
                storedBookmarks.push(key);
                bookmarkBtn.classList.add('is-saved');
                newlySaved = true;
            } else {
                storedBookmarks.splice(index, 1);
                bookmarkBtn.classList.remove('is-saved');
            }

            localStorage.setItem(bookmarkStorageKey, JSON.stringify(storedBookmarks));
            
            // Sync with other cards and elements
            syncServiceStates();

            if (typeof showToast === 'function') {
                if (newlySaved) {
                    showToast(`"${title}" berhasil ditambahkan ke Tersimpan!`, 'success');
                } else {
                    showToast(`"${title}" dihapus dari Tersimpan.`, 'info');
                }
            }
        }

        // Handle saved shortcut toggle
        const savedShortcutBtn = event.target.closest('[data-shortcut-saved-toggle]');
        if (savedShortcutBtn) {
            switchTab('tersimpan');
        }

        // Handle custom category edit toggle click
        const categoryEditToggle = event.target.closest('[data-category-edit-toggle]');
        if (categoryEditToggle) {
            event.preventDefault();
            event.stopPropagation();
            const categoryName = categoryEditToggle.getAttribute('data-category-edit-toggle');
            const dbId = categoryEditToggle.getAttribute('data-category-db-id') || null;
            categoryBuilderState.editCategoryDbId = dbId;
            openCategoryBuilderForEdit(categoryName);
            return;
        }

        // Handle folder card click
        const folderCard = event.target.closest('[data-category-folder]');
        if (folderCard) {
            const categoryName = folderCard.getAttribute('data-category-folder');
            
            // Switch tab to tersimpan
            switchTab('tersimpan');
            
            // Apply category filter
            applyShortcutFilter(categoryName);
        }
    });

    // Card dropdown category search
    document.addEventListener('input', function (event) {
        const searchInput = event.target.closest('[data-card-category-search]');
        if (!searchInput) return;

        const query = searchInput.value.toLowerCase();
        const menu = searchInput.closest('[data-card-dropdown-menu]');
        const items = menu.querySelectorAll('[data-card-category-option]');

        items.forEach(item => {
            const label = item.getAttribute('data-card-category-option').toLowerCase();
            if (label.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (profilePanel && !profilePanel.hidden) {
            closeProfilePanel();
            return;
        }

        if (categoryBuilder.modal && categoryBuilder.modal.classList.contains('is-open')) {
            closeCategoryBuilder();
            return;
        }

        if (shortcutCategoryControls.toggle && shortcutCategoryControls.toggle.getAttribute('aria-expanded') === 'true') {
            closeCategoryMenu(shortcutCategoryControls);
            return;
        }
    });

    syncCustomCategories();
    syncServiceStates();
    updateCategoryFilter(shortcutCategoryControls);
    updateCategoryBuilderEmptyState();
    applyShortcutFilter(readShortcutFilter());

    // Initialize static premium selects on load
    const staticSelects = document.querySelectorAll('#linkKategori');
    staticSelects.forEach(function (select) {
        initPremiumSelect(select);
    });

    // Restore active tab dynamically on page load
    const storedBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
    const defaultTab = storedBookmarks.length === 0 ? 'semua' : 'tersimpan';
    const persistedTab = localStorage.getItem(activeTabStorageKey) || defaultTab;
    switchTab(persistedTab);

    // Listen for Beranda sidebar link click to dynamically reset the tab state
    const berandaSidebarLink = document.querySelector('[data-sidebar-beranda]');
    if (berandaSidebarLink) {
        berandaSidebarLink.addEventListener('click', function () {
            const currentBookmarks = JSON.parse(localStorage.getItem(bookmarkStorageKey) || '[]');
            const resetTab = currentBookmarks.length === 0 ? 'semua' : 'tersimpan';
            localStorage.setItem(activeTabStorageKey, resetTab);
        });
    }

    window.openPasswordModal = function() {
        const m = document.getElementById('passwordModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        closeProfilePanel();
    };

    window.closePasswordModal = function() {
        const m = document.getElementById('passwordModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    };

    const originalOpenProfileModal = window.openProfileModal;
    window.openProfileModal = function() {
        if (typeof originalOpenProfileModal === 'function') originalOpenProfileModal();
        updateBodyLock();
    };

    const originalCloseProfileModal = window.closeProfileModal;
    window.closeProfileModal = function() {
        if (typeof originalCloseProfileModal === 'function') originalCloseProfileModal();
        setTimeout(updateBodyLock, 350);
    };

    window.openCategoryBuilder = openCategoryBuilder;
    window.openCategoryBuilderForEdit = openCategoryBuilderForEdit;
    window.closeCategoryBuilder = closeCategoryBuilder;
    window.updateBodyLock = updateBodyLock;

    updateBodyLock();
});

window.openLinkModal = function(id = '', title = '', url = '', desc = '', role = '', tags = '[]', id_kategori = '') {
    const modal = document.getElementById('linkModal');
    const form = document.getElementById('linkForm');
    if (!modal || !form) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (id) {
        form.action = `/pengguna/links/${id}`;
        document.getElementById('linkMethod').value = 'PUT';
        document.getElementById('linkModalTitle').innerText = 'Edit Link';
    } else {
        form.action = window.PolTree.storeUserLinkRoute;
        document.getElementById('linkMethod').value = 'POST';
        document.getElementById('linkModalTitle').innerText = 'Tambah Link';
    }
    document.getElementById('linkTitle').value = title;
    document.getElementById('linkUrl').value = url;
    document.getElementById('linkDescription').value = desc;
    
    const kategoriSelect = document.getElementById('linkKategori');
    if (kategoriSelect) {
        kategoriSelect.value = id_kategori;
        if (typeof kategoriSelect.refreshPremiumSelect === 'function') {
            kategoriSelect.refreshPremiumSelect();
        }
    }

    // Reset quick category form
    const quickContainer = document.getElementById('userQuickCategoryContainer');
    if (quickContainer) {
        quickContainer.style.display = 'none';
    }
    const quickInput = document.getElementById('user_quick_nama_kategori');
    if (quickInput) {
        quickInput.value = '';
    }

    const roleInput = document.getElementById('linkRole');
    if (roleInput) {
        roleInput.value = role;
    }

    const selectedTags = typeof tags === 'string' ? JSON.parse(tags) : tags;
    document.querySelectorAll('.user-tag-checkbox').forEach(cb => {
        cb.checked = selectedTags.includes(parseInt(cb.value));
    });

    if (typeof window.updateBodyLock === 'function') {
        window.updateBodyLock();
    }
};

window.closeLinkModal = function() {
    const m = document.getElementById('linkModal');
    if (!m) return;
    m.classList.add('closing');
    setTimeout(() => {
        m.classList.add('hidden');
        m.classList.remove('flex', 'closing');
        
        // Reset quick category form on close
        const quickContainer = document.getElementById('userQuickCategoryContainer');
        if (quickContainer) {
            quickContainer.style.display = 'none';
        }
        const quickInput = document.getElementById('user_quick_nama_kategori');
        if (quickInput) {
            quickInput.value = '';
        }

        if (typeof window.updateBodyLock === 'function') {
            window.updateBodyLock();
        }
    }, 300);
};
