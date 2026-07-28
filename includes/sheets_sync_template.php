<?php
/**
 * Al Foz Islamic Institute - Google Sheets Sync Template
 */
?>

<!-- Include Firebase Compat SDKs from CDN -->
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>

<!-- Main Portal Area -->
<div class="flex-grow flex flex-col min-h-screen w-full bg-transparent page-transition">
  <div class="p-6 md:p-8 flex-grow">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Title / Breadcrumbs -->
    <div class="mb-8">
      <div class="flex items-center gap-2 text-xs text-primary/60 font-semibold uppercase tracking-wider mb-2">
        <span>Administration</span>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-primary font-bold">Google Sheets Sync</span>
      </div>
      <h1 class="text-2xl sm:text-3xl font-black text-primary tracking-tight">Google Sheets Integration Hub</h1>
      <p class="text-xs sm:text-sm text-primary/70 mt-1 max-w-2xl">
        Synchronize your student registries, tutor databases, attendance sheets, and financial records directly to a Google Spreadsheet.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Left Column: Settings and Sync Actions -->
      <div class="lg:col-span-8 space-y-8">
        
        <!-- Authentication Card -->
        <div class="bg-white rounded-[24px] p-6 sm:p-8 border border-primary/10 shadow-sm relative overflow-hidden transition-all duration-300">
          <div class="absolute -right-12 -top-12 w-32 h-32 rounded-full bg-emerald-500/5 blur-2xl pointer-events-none"></div>
          
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
              </div>
              <div>
                <h3 class="font-extrabold text-base text-primary">Google Sheets API Authorization</h3>
                <p class="text-xs text-primary/60 mt-0.5" id="auth-sub-status">Please sign in with your Google Workspace or personal Google Account.</p>
              </div>
            </div>
            
            <div id="auth-actions-container">
              <!-- Loading State -->
              <div id="auth-loading" class="flex items-center gap-2 text-xs text-primary/60 font-bold uppercase tracking-wider">
                <div class="w-4 h-4 border-2 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                <span>Checking State...</span>
              </div>
              
              <!-- Sign In Button -->
              <button id="btn-google-signin" class="hidden gsi-material-button hover:scale-[1.02] active:scale-[0.98] transition-all" onclick="handleGoogleSignIn()">
                <div class="gsi-material-button-state"></div>
                <div class="gsi-material-button-content-wrapper">
                  <div class="gsi-material-button-icon">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" style="display: block;">
                      <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                      <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                      <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                      <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                      <path fill="none" d="M0 0h48v48H0z"></path>
                    </svg>
                  </div>
                  <span class="gsi-material-button-contents font-bold text-xs">Sign in with Google</span>
                </div>
              </button>
              
              <!-- Sign Out State -->
              <div id="auth-signed-in" class="hidden flex items-center gap-3">
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-200/50 rounded-xl px-3 py-1.5">
                  <img id="user-avatar" class="w-5 h-5 rounded-full object-cover" src="" referrerPolicy="no-referrer" alt="Google Avatar">
                  <span id="user-name" class="text-xs font-bold text-emerald-800">Tutor Panel</span>
                </div>
                <button class="text-[10px] font-bold text-rose-600 uppercase tracking-widest hover:underline" onclick="handleGoogleSignOut()">Disconnect</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Sync Configuration Panel (Shown when authorized) -->
        <div id="sync-config-panel" class="hidden space-y-8">
          
          <!-- Spreadsheet Selection Card -->
          <div class="bg-white rounded-[24px] p-6 sm:p-8 border border-primary/10 shadow-sm space-y-6">
            <h3 class="font-extrabold text-base text-primary flex items-center gap-2">
              <i data-lucide="file-spreadsheet" class="w-5 h-5 text-emerald-600"></i>
              <span>Target Spreadsheet Setup</span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- New Spreadsheet Option -->
              <div class="border border-primary/10 rounded-2xl p-5 hover:border-emerald-500/50 transition-all cursor-pointer relative" id="opt-create-sheet" onclick="selectSheetOption('create')">
                <div class="absolute right-4 top-4">
                  <div class="w-5 h-5 rounded-full border-2 border-primary/20 flex items-center justify-center" id="radio-create">
                    <div class="w-2.5 h-2.5 rounded-full bg-primary hidden" id="radio-create-dot"></div>
                  </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary mb-4">
                  <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-primary">Create Brand New Spreadsheet</h4>
                <p class="text-[11px] text-primary/60 mt-1.5 leading-relaxed">Recommended. Automatically configures a fresh sheet with separate tabs for students, teachers, fees, and attendance.</p>
              </div>

              <!-- Existing Spreadsheet Option -->
              <div class="border border-primary/10 rounded-2xl p-5 hover:border-emerald-500/50 transition-all cursor-pointer relative" id="opt-connect-sheet" onclick="selectSheetOption('connect')">
                <div class="absolute right-4 top-4">
                  <div class="w-5 h-5 rounded-full border-2 border-primary/20 flex items-center justify-center" id="radio-connect">
                    <div class="w-2.5 h-2.5 rounded-full bg-primary hidden" id="radio-connect-dot"></div>
                  </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4">
                  <i data-lucide="link" class="w-5 h-5"></i>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-primary">Use Existing Spreadsheet</h4>
                <p class="text-[11px] text-primary/60 mt-1.5 leading-relaxed">Connect to an already existing spreadsheet. You'll need to specify its Google Spreadsheet ID.</p>
              </div>
            </div>

            <!-- Spreadsheet Form Inputs -->
            <div class="space-y-4 pt-2">
              <div id="input-create-container" class="space-y-2">
                <label class="text-[10px] font-extrabold uppercase tracking-widest text-primary/75 block">New Spreadsheet Title</label>
                <input type="text" id="new-spreadsheet-title" class="w-full text-xs font-semibold" value="Al Foz Islamic Institute ERP Export - <?php echo date('M Y'); ?>">
              </div>
              
              <div id="input-connect-container" class="hidden space-y-2">
                <label class="text-[10px] font-extrabold uppercase tracking-widest text-primary/75 block">Google Spreadsheet ID</label>
                <input type="text" id="existing-spreadsheet-id" class="w-full text-xs font-semibold placeholder:text-primary/30" placeholder="e.g. 1aBCDeFGhiJKLMNOPqRStUvwXyZ...">
                <p class="text-[10px] text-primary/50 leading-relaxed font-semibold">The spreadsheet ID is the long string of characters in the URL: sheets.google.com/spreadsheets/d/<strong class="text-primary/80">[Spreadsheet-ID]</strong>/edit</p>
              </div>
            </div>
          </div>

          <!-- Dataset Selection Card -->
          <div class="bg-white rounded-[24px] p-6 sm:p-8 border border-primary/10 shadow-sm space-y-6">
            <div>
              <h3 class="font-extrabold text-base text-primary">Registries & Datasets to Sync</h3>
              <p class="text-xs text-primary/60 mt-1">Select the specific modules you wish to synchronize to Google Sheets.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Students -->
              <label class="flex items-center gap-4 border border-primary/10 rounded-2xl p-4 cursor-pointer hover:bg-primary/5 select-none transition-all">
                <input type="checkbox" id="sync-students" class="w-4.5 h-4.5 accent-primary rounded cursor-pointer" checked>
                <div>
                  <h4 class="font-bold text-xs text-primary">Students Registry</h4>
                  <p class="text-[10px] text-primary/60 mt-0.5">Names, courses, fees, status & contact emails.</p>
                </div>
              </label>

              <!-- Teachers -->
              <label class="flex items-center gap-4 border border-primary/10 rounded-2xl p-4 cursor-pointer hover:bg-primary/5 select-none transition-all">
                <input type="checkbox" id="sync-teachers" class="w-4.5 h-4.5 accent-primary rounded cursor-pointer" checked>
                <div>
                  <h4 class="font-bold text-xs text-primary">Teachers Registry</h4>
                  <p class="text-[10px] text-primary/60 mt-0.5">Faculty listing, salaries, taught courses & statuses.</p>
                </div>
              </label>

              <!-- Fees -->
              <label class="flex items-center gap-4 border border-primary/10 rounded-2xl p-4 cursor-pointer hover:bg-primary/5 select-none transition-all">
                <input type="checkbox" id="sync-fees" class="w-4.5 h-4.5 accent-primary rounded cursor-pointer" checked>
                <div>
                  <h4 class="font-bold text-xs text-primary">Fee Logs & Transactions</h4>
                  <p class="text-[10px] text-primary/60 mt-0.5">Tuition invoice balances, payments & due dates.</p>
                </div>
              </label>

              <!-- Attendance -->
              <label class="flex items-center gap-4 border border-primary/10 rounded-2xl p-4 cursor-pointer hover:bg-primary/5 select-none transition-all">
                <input type="checkbox" id="sync-attendance" class="w-4.5 h-4.5 accent-primary rounded cursor-pointer" checked>
                <div>
                  <h4 class="font-bold text-xs text-primary">Tutor Attendance Logs</h4>
                  <p class="text-[10px] text-primary/60 mt-0.5">Lesson tracking, duration logs, dates & details.</p>
                </div>
              </label>
            </div>

            <!-- Action Button container -->
            <div class="h-px bg-primary/10 my-6"></div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <span class="text-xs text-primary/50 font-semibold" id="sync-target-indicator">Syncing 4 registries to Google Sheets</span>
              <button class="bg-primary text-white font-extrabold text-xs uppercase tracking-widest px-8 py-4 rounded-xl flex items-center justify-center gap-2 hover:opacity-95 shadow-md self-end" onclick="initiateDataSync()">
                <i data-lucide="refresh-cw" class="w-4 h-4" id="sync-icon"></i>
                <span id="sync-btn-text">Synchronize Now</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Status / Log Terminal (Fades in during Sync) -->
          <div id="sync-terminal" class="hidden bg-slate-900 border border-slate-800 text-slate-300 rounded-2xl p-6 font-mono text-[11px] leading-relaxed space-y-3 shadow-inner">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3 mb-2">
              <span class="text-xs text-emerald-400 font-bold uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Synchronization Live Console</span>
              </span>
              <span class="text-[10px] text-slate-500" id="sync-percentage">0% COMPLETE</span>
            </div>
            
            <div id="terminal-logs" class="space-y-1.5 max-h-48 overflow-y-auto">
              <!-- Logs will be appended here dynamically -->
            </div>

            <div id="terminal-success-card" class="hidden bg-emerald-950/40 border border-emerald-900/50 p-4 rounded-xl flex items-start gap-3 mt-4">
              <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                <i data-lucide="check" class="w-4 h-4"></i>
              </div>
              <div class="flex-grow">
                <h4 class="font-bold text-xs text-emerald-200">Synchronization Completed Successfully!</h4>
                <p class="text-[10px] text-emerald-400/80 mt-1">Your ERP registries are fully synchronized. You can access the document via the link below.</p>
                <div class="mt-3">
                  <a id="spreadsheet-external-link" href="#" target="_blank" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[10px] px-3.5 py-2 rounded-lg transition-all">
                    <span>Open in Google Sheets</span>
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Authorized Panel Placeholder -->
        <div id="no-auth-placeholder" class="bg-white rounded-[24px] p-10 border border-primary/10 shadow-sm text-center space-y-4">
          <div class="w-16 h-16 rounded-2xl bg-primary/5 text-primary flex items-center justify-center mx-auto">
            <i data-lucide="lock" class="w-8 h-8"></i>
          </div>
          <div>
            <h3 class="font-extrabold text-base text-primary">Integration Suspended</h3>
            <p class="text-xs text-primary/60 mt-1 max-w-md mx-auto leading-relaxed">
              To proceed with exporting or syncing administrative data to Google Sheets, you must first connect and authorize access using your Google Account above.
            </p>
          </div>
        </div>

      </div>

      <!-- Right Column: Sidebar Info Cards -->
      <div class="lg:col-span-4 space-y-8">
        
        <!-- Active Integration Stats -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm space-y-4">
          <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary">Sheets Sync Statistics</h3>
          
          <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between text-xs border-b border-primary/5 pb-2.5">
              <span class="text-primary/70 font-semibold">Tutors Active:</span>
              <span class="font-extrabold text-primary" id="stat-teachers">-</span>
            </div>
            <div class="flex items-center justify-between text-xs border-b border-primary/5 pb-2.5">
              <span class="text-primary/70 font-semibold">Enrolled Seekers:</span>
              <span class="font-extrabold text-primary" id="stat-students">-</span>
            </div>
            <div class="flex items-center justify-between text-xs border-b border-primary/5 pb-2.5">
              <span class="text-primary/70 font-semibold">Tuition Invoices:</span>
              <span class="font-extrabold text-primary" id="stat-fees">-</span>
            </div>
            <div class="flex items-center justify-between text-xs">
              <span class="text-primary/70 font-semibold">Attendance Logs:</span>
              <span class="font-extrabold text-primary" id="stat-attendance">-</span>
            </div>
          </div>
        </div>

        <!-- Security & Workspace compliance -->
        <div class="bg-white rounded-[24px] p-6 border border-primary/10 shadow-sm space-y-3.5">
          <h3 class="font-extrabold text-sm uppercase tracking-wider text-primary">Data Security & Compliance</h3>
          <p class="text-[10px] sm:text-xs text-primary/60 leading-relaxed font-semibold">
            Google Sheets integration utilizes direct Google OAuth 2.0 protocol. The application only accesses spreadsheets explicitly created or synced by you under the authorized scope of <strong class="text-primary">drive.file</strong> and <strong class="text-primary">spreadsheets</strong>.
          </p>
          <div class="h-px bg-primary/10 my-2"></div>
          <p class="text-[10px] text-primary/50 leading-relaxed font-bold">
            Data mutations inside Google Sheets will completely overwrite targeted spreadsheets during sync cycles. Live backups are encouraged.
          </p>
        </div>

      </div>
    </div>

  </div>
</div>

<style>
/* Button override classes */
.gsi-material-button {
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  -webkit-appearance: none;
  background-color: WHITE;
  background-image: none;
  border: 1px solid #747775;
  -webkit-border-radius: 12px;
  border-radius: 12px;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  color: #1f1f1f;
  cursor: pointer;
  display: inline-block;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 500;
  height: 40px;
  letter-spacing: 0.25px;
  outline: none;
  overflow: hidden;
  padding: 0 12px;
  position: relative;
  text-align: center;
  -webkit-transition: background-color .218s, border-color .218s, box-shadow .218s;
  transition: background-color .218s, border-color .218s, box-shadow .218s;
  vertical-align: middle;
  white-space: nowrap;
  width: auto;
  max-width: 400px;
  min-width: min-content;
}

.gsi-material-button .gsi-material-button-icon {
  height: 20px;
  min-width: 20px;
  width: 20px;
}

.gsi-material-button .gsi-material-button-content-wrapper {
  -webkit-align-items: center;
  align-items: center;
  display: flex;
  -webkit-flex-direction: row;
  flex-direction: row;
  -webkit-flex-wrap: nowrap;
  flex-wrap: nowrap;
  height: 100%;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

.gsi-material-button .gsi-material-button-contents {
  -webkit-flex-grow: 1;
  flex-grow: 1;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  letter-spacing: 0.25px;
  margin-left: 12px;
  margin-right: 12px;
  text-align: left;
}

.gsi-material-button .gsi-material-button-state {
  -webkit-transition: opacity .218s;
  transition: opacity .218s;
  bottom: 0;
  left: 0;
  opacity: 0;
  position: absolute;
  right: 0;
  top: 0;
}

.gsi-material-button:hover .gsi-material-button-state {
  background-color: #303030;
  opacity: 0.04;
}

.gsi-material-button:active .gsi-material-button-state {
  background-color: #303030;
  opacity: 0.12;
}
</style>

<script>
// Firebase Config from applet environment
const firebaseAppConfig = <?php echo file_get_contents(__DIR__ . '/../firebase-applet-config.json'); ?>;

// Variables
let googleAuthToken = null;
let currentSelection = 'create'; // 'create' or 'connect'
let erpData = null;

// Initialize Firebase
if (typeof firebase !== 'undefined' && firebaseAppConfig) {
  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseAppConfig);
  }
}

const firebaseAuth = firebase.auth();

// Initial Check of Session
document.addEventListener('DOMContentLoaded', () => {
  // Update Lucide Icons
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
  
  // Set initial target sheet selection borders
  selectSheetOption('create');
  
  // Setup listener for checkboxes
  const checkboxes = ['sync-students', 'sync-teachers', 'sync-fees', 'sync-attendance'];
  checkboxes.forEach(id => {
    document.getElementById(id).addEventListener('change', updateDatasetIndicator);
  });
  
  // Load ERP stats from local server API
  loadErpStats();

  // Watch Auth State
  firebaseAuth.onAuthStateChanged(async (user) => {
    if (user) {
      logToConsole("Checking credentials...");
      // For Google Access Token, retrieve from cache if possible
      const cachedToken = localStorage.getItem('google_sheets_oauth_token');
      const tokenExpiry = localStorage.getItem('google_sheets_oauth_token_expiry');
      
      if (cachedToken && tokenExpiry && Date.now() < parseInt(tokenExpiry)) {
        googleAuthToken = cachedToken;
        showSignedInUI(user);
        logToConsole("Successfully re-authenticated with Google Sheets API.");
      } else {
        // Token expired or not available, need to re-login to retrieve token
        localStorage.removeItem('google_sheets_oauth_token');
        localStorage.removeItem('google_sheets_oauth_token_expiry');
        showSignedOutUI();
        logToConsole("Access token expired. Please connect your Google account.");
      }
    } else {
      showSignedOutUI();
    }
  });
});

async function loadErpStats() {
  try {
    const sSuffix = window.location.search;
    const response = await fetch('/api/export-data' + sSuffix);
    if (!response.ok) {
      throw new Error("Unable to fetch registry counts.");
    }
    erpData = await response.json();
    
    // Set counts in UI
    document.getElementById('stat-teachers').textContent = erpData.teachers.length + " Faculty";
    document.getElementById('stat-students').textContent = erpData.students.length + " Seekers";
    document.getElementById('stat-fees').textContent = erpData.fees.length + " Invoices";
    document.getElementById('stat-attendance').textContent = erpData.attendance.length + " Logs";
    
    updateDatasetIndicator();
  } catch (error) {
    console.error("Failed to load ERP stats", error);
    logToConsole("Failed to load local ERP data: " + error.message, "error");
  }
}

function updateDatasetIndicator() {
  let count = 0;
  if (document.getElementById('sync-students').checked) count++;
  if (document.getElementById('sync-teachers').checked) count++;
  if (document.getElementById('sync-fees').checked) count++;
  if (document.getElementById('sync-attendance').checked) count++;
  
  document.getElementById('sync-target-indicator').textContent = `Syncing ${count} registries to Google Sheets`;
}

function selectSheetOption(option) {
  currentSelection = option;
  
  const createOpt = document.getElementById('opt-create-sheet');
  const connectOpt = document.getElementById('opt-connect-sheet');
  const createDot = document.getElementById('radio-create-dot');
  const connectDot = document.getElementById('radio-connect-dot');
  const createRadio = document.getElementById('radio-create');
  const connectRadio = document.getElementById('radio-connect');
  
  const createInput = document.getElementById('input-create-container');
  const connectInput = document.getElementById('input-connect-container');
  
  if (option === 'create') {
    createOpt.classList.add('border-emerald-500/50', 'bg-emerald-50/10');
    connectOpt.classList.remove('border-emerald-500/50', 'bg-emerald-50/10');
    createRadio.classList.add('border-emerald-500');
    connectRadio.classList.remove('border-emerald-500');
    createDot.classList.remove('hidden');
    connectDot.classList.add('hidden');
    
    createInput.classList.remove('hidden');
    connectInput.classList.add('hidden');
    
    // Retrieve cached spreadsheet ID if any
    const cachedId = localStorage.getItem('google_sheets_spreadsheet_id');
    if (cachedId) {
      const parentLabel = document.querySelector('#opt-create-sheet p');
      if (parentLabel) {
        parentLabel.innerHTML = `Recommended. Create a new sheet, or sync directly to your previously created sheet:<br><span class="font-bold font-mono text-emerald-700 text-[10px] break-all">${cachedId}</span>`;
      }
    }
  } else {
    connectOpt.classList.add('border-emerald-500/50', 'bg-emerald-50/10');
    createOpt.classList.remove('border-emerald-500/50', 'bg-emerald-50/10');
    connectRadio.classList.add('border-emerald-500');
    createRadio.classList.remove('border-emerald-500');
    connectDot.classList.remove('hidden');
    createDot.classList.add('hidden');
    
    connectInput.classList.remove('hidden');
    createInput.classList.add('hidden');
    
    // Fill in last connected spreadsheet ID if any
    const cachedId = localStorage.getItem('google_sheets_spreadsheet_id');
    if (cachedId) {
      document.getElementById('existing-spreadsheet-id').value = cachedId;
    }
  }
}

// Google Authentication
async function handleGoogleSignIn() {
  const provider = new firebase.auth.GoogleAuthProvider();
  // Request sheets and drive scope
  provider.addScope('https://www.googleapis.com/auth/spreadsheets');
  provider.addScope('https://www.googleapis.com/auth/drive.file');
  
  logToConsole("Requesting Google authorization...");
  try {
    const result = await firebaseAuth.signInWithPopup(provider);
    const credential = result.credential;
    googleAuthToken = credential.accessToken;
    
    // Token lasts 1 hour. Cache it securely with timestamp.
    localStorage.setItem('google_sheets_oauth_token', googleAuthToken);
    localStorage.setItem('google_sheets_oauth_token_expiry', (Date.now() + 3500 * 1000).toString());
    
    showSignedInUI(result.user);
    logToConsole("Google Account successfully connected.");
  } catch (error) {
    console.error("Sign-in failed", error);
    alert("Authorization failed: " + error.message);
    logToConsole("Authorization error: " + error.message, "error");
  }
}

function handleGoogleSignOut() {
  const confirmed = window.confirm("Are you sure you want to disconnect your Google account from Google Sheets Sync?");
  if (!confirmed) return;
  
  firebaseAuth.signOut().then(() => {
    googleAuthToken = null;
    localStorage.removeItem('google_sheets_oauth_token');
    localStorage.removeItem('google_sheets_oauth_token_expiry');
    showSignedOutUI();
    logToConsole("Disconnected Google Sheets authentication.");
  });
}

function showSignedInUI(user) {
  document.getElementById('auth-loading').classList.add('hidden');
  document.getElementById('btn-google-signin').classList.add('hidden');
  document.getElementById('auth-signed-in').classList.remove('hidden');
  
  document.getElementById('user-avatar').src = user.photoURL || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.displayName || 'G')}`;
  document.getElementById('user-name').textContent = user.displayName || user.email;
  document.getElementById('auth-sub-status').textContent = `Connected as ${user.email}. Ready for export.`;
  
  document.getElementById('sync-config-panel').classList.remove('hidden');
  document.getElementById('no-auth-placeholder').classList.add('hidden');
}

function showSignedOutUI() {
  document.getElementById('auth-loading').classList.add('hidden');
  document.getElementById('btn-google-signin').classList.remove('hidden');
  document.getElementById('auth-signed-in').classList.add('hidden');
  document.getElementById('auth-sub-status').textContent = "Please sign in with your Google Workspace or personal Google Account.";
  
  document.getElementById('sync-config-panel').classList.add('hidden');
  document.getElementById('no-auth-placeholder').classList.remove('hidden');
}

// Logger helper
function logToConsole(message, type = "info") {
  const terminal = document.getElementById('sync-terminal');
  const logsContainer = document.getElementById('terminal-logs');
  
  if (terminal.classList.contains('hidden')) {
    terminal.classList.remove('hidden');
  }
  
  const timestamp = new Date().toTimeString().split(" ")[0];
  let colorClass = "text-slate-300";
  let prefix = "[INFO]";
  
  if (type === "success") {
    colorClass = "text-emerald-400 font-bold";
    prefix = "[SUCCESS]";
  } else if (type === "error") {
    colorClass = "text-rose-400 font-bold";
    prefix = "[ERROR]";
  } else if (type === "warn") {
    colorClass = "text-amber-400";
    prefix = "[WARN]";
  }
  
  const logLine = document.createElement('div');
  logLine.className = `${colorClass} py-0.5`;
  logLine.innerHTML = `<span class="text-slate-500 mr-2">${timestamp}</span><span class="mr-1.5 font-bold">${prefix}</span>${message}`;
  
  logsContainer.appendChild(logLine);
  logsContainer.scrollTop = logsContainer.scrollHeight;
}

// Data synchronization logic
async function initiateDataSync() {
  // Confirm user permission before sync (Mandatory for mutating/creating workspace documents)
  const confirmed = window.confirm("Are you sure you want to proceed with Google Sheets Synchronization? Existing spreadsheets matching the connection ID will have their data fully updated.");
  if (!confirmed) return;

  const btnText = document.getElementById('sync-btn-text');
  const syncIcon = document.getElementById('sync-icon');
  
  // Set loading state
  btnText.textContent = "Synchronizing...";
  syncIcon.classList.add('animate-spin');
  document.getElementById('terminal-success-card').classList.add('hidden');
  
  // Clear old logs
  document.getElementById('terminal-logs').innerHTML = "";
  setSyncPercentage(5);
  
  try {
    logToConsole("Sync initialization started.");
    
    // 1. Fetch live data
    logToConsole("Retrieving local ERP records from system storage...");
    await loadErpStats(); // Refresh local data cache
    if (!erpData) {
      throw new Error("Local database is currently unavailable.");
    }
    setSyncPercentage(15);
    
    // 2. Resolve Spreadsheet ID
    let spreadsheetId = null;
    if (currentSelection === 'create') {
      // Check if we can reuse the previously created spreadsheet
      const cachedId = localStorage.getItem('google_sheets_spreadsheet_id');
      if (cachedId) {
        const reuse = window.confirm(`A previously synced spreadsheet was found:\nID: ${cachedId}\n\nWould you like to sync directly to this existing spreadsheet? (Click Cancel to create a completely new one instead)`);
        if (reuse) {
          spreadsheetId = cachedId;
          logToConsole(`Reusing existing synchronized spreadsheet ID: ${spreadsheetId}`);
        }
      }
      
      if (!spreadsheetId) {
        logToConsole("Creating fresh Google Spreadsheet...");
        const title = document.getElementById('new-spreadsheet-title').value || "Al Foz ERP Sync";
        spreadsheetId = await createNewSpreadsheet(title);
        localStorage.setItem('google_sheets_spreadsheet_id', spreadsheetId);
        logToConsole(`Created new spreadsheet with ID: ${spreadsheetId}`, "success");
      }
    } else {
      spreadsheetId = document.getElementById('existing-spreadsheet-id').value.trim();
      if (!spreadsheetId) {
        throw new Error("Spreadsheet ID is required for connection.");
      }
      logToConsole(`Connecting to existing spreadsheet ID: ${spreadsheetId}...`);
      localStorage.setItem('google_sheets_spreadsheet_id', spreadsheetId);
    }
    setSyncPercentage(40);
    
    // 3. Format & Export individual datasets
    const syncStudents = document.getElementById('sync-students').checked;
    const syncTeachers = document.getElementById('sync-teachers').checked;
    const syncFees = document.getElementById('sync-fees').checked;
    const syncAttendance = document.getElementById('sync-attendance').checked;
    
    let totalSteps = (syncStudents ? 1 : 0) + (syncTeachers ? 1 : 0) + (syncFees ? 1 : 0) + (syncAttendance ? 1 : 0);
    let completedSteps = 0;
    
    if (syncStudents) {
      logToConsole("Formatting & exporting Student Registry...");
      await syncDataset(spreadsheetId, "Students Registry", formatStudentData());
      completedSteps++;
      setSyncPercentage(40 + Math.round((completedSteps / totalSteps) * 50));
    }
    
    if (syncTeachers) {
      logToConsole("Formatting & exporting Teachers Registry...");
      await syncDataset(spreadsheetId, "Teachers Registry", formatTeacherData());
      completedSteps++;
      setSyncPercentage(40 + Math.round((completedSteps / totalSteps) * 50));
    }
    
    if (syncFees) {
      logToConsole("Formatting & exporting Fees Records...");
      await syncDataset(spreadsheetId, "Fees Logs", formatFeeData());
      completedSteps++;
      setSyncPercentage(40 + Math.round((completedSteps / totalSteps) * 50));
    }
    
    if (syncAttendance) {
      logToConsole("Formatting & exporting Attendance History...");
      await syncDataset(spreadsheetId, "Attendance Logs", formatAttendanceData());
      completedSteps++;
      setSyncPercentage(40 + Math.round((completedSteps / totalSteps) * 50));
    }
    
    setSyncPercentage(100);
    logToConsole("All select tables updated successfully in Google Drive.", "success");
    
    // Show success details
    const successCard = document.getElementById('terminal-success-card');
    const extLink = document.getElementById('spreadsheet-external-link');
    successCard.classList.remove('hidden');
    extLink.href = `https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit`;
    
    // Update Lucide Icons for dynamic checkmark
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
    
  } catch (error) {
    console.error(error);
    logToConsole("Sync aborted due to error: " + error.message, "error");
    alert("Synchronization failed: " + error.message);
  } finally {
    btnText.textContent = "Synchronize Now";
    syncIcon.classList.remove('animate-spin');
  }
}

function setSyncPercentage(pct) {
  document.getElementById('sync-percentage').textContent = `${pct}% COMPLETE`;
}

// Google Sheets API Actions
async function createNewSpreadsheet(title) {
  const url = 'https://sheets.googleapis.com/v4/spreadsheets';
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${googleAuthToken}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      properties: {
        title: title
      },
      sheets: [
        { properties: { title: "Students Registry" } },
        { properties: { title: "Teachers Registry" } },
        { properties: { title: "Fees Logs" } },
        { properties: { title: "Attendance Logs" } }
      ]
    })
  });
  
  if (!response.ok) {
    const errObj = await response.json().catch(() => ({}));
    const detail = errObj.error ? errObj.error.message : response.statusText;
    throw new Error("Unable to create spreadsheet. " + detail);
  }
  
  const data = await response.json();
  return data.spreadsheetId;
}

async function syncDataset(spreadsheetId, sheetTitle, dataRows) {
  // Try to push to spreadsheet. If the tab doesn't exist, we will attempt to create the sheet first.
  const range = `'${sheetTitle}'!A1`;
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}/values/${encodeURIComponent(range)}?valueInputOption=USER_ENTERED`;
  
  let response = await fetch(url, {
    method: 'PUT',
    headers: {
      'Authorization': `Bearer ${googleAuthToken}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      values: dataRows
    })
  });
  
  if (!response.ok) {
    const errObj = await response.json().catch(() => ({}));
    if (errObj.error && errObj.error.message && errObj.error.message.includes("Unable to parse range")) {
      // Sheet tab probably doesn't exist, let's create the sheet tab first
      logToConsole(`Sheet tab "${sheetTitle}" does not exist. Creating dynamically...`, "warn");
      await createSheetTab(spreadsheetId, sheetTitle);
      
      // Retry writing values
      response = await fetch(url, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${googleAuthToken}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          values: dataRows
        })
      });
      if (!response.ok) {
        const retryErr = await response.json().catch(() => ({}));
        throw new Error(`Failed to write values after tab creation: ${retryErr.error ? retryErr.error.message : response.statusText}`);
      }
    } else {
      throw new Error(`Google Sheets API Error: ${errObj.error ? errObj.error.message : response.statusText}`);
    }
  }
  
  logToConsole(`Synchronized dataset [${sheetTitle}] (${dataRows.length} rows including headers).`, "success");
}

async function createSheetTab(spreadsheetId, sheetTitle) {
  const url = `https://sheets.googleapis.com/v4/spreadsheets/${spreadsheetId}:batchUpdate`;
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${googleAuthToken}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      requests: [
        {
          addSheet: {
            properties: {
              title: sheetTitle
            }
          }
        }
      ]
    })
  });
  
  if (!response.ok) {
    const errObj = await response.json().catch(() => ({}));
    throw new Error(`Failed to create sheet tab "${sheetTitle}": ${errObj.error ? errObj.error.message : response.statusText}`);
  }
}

// Data formatters
function formatStudentData() {
  const headers = ["Roll Number", "Full Name", "Gender", "Country", "Course / Class", "Monthly Fee", "Fee Currency", "Enrollment Date", "Tutor Assigned", "Status"];
  const rows = erpData.students.map(s => [
    s.roll_no || s.student_id || '',
    s.name || '',
    s.gender || 'Male',
    s.country || '',
    s.course || '',
    s.monthly_fee || '0',
    s.currency || 'PKR',
    s.joining_date || s.enrollment_date || '',
    s.teacher_name || '',
    s.status || 'Active'
  ]);
  return [headers, ...rows];
}

function formatTeacherData() {
  const headers = ["Tutor ID", "Tutor Name", "Contact No", "Course Taught", "Status", "Base Salary", "Currency", "Tutor Email"];
  const rows = erpData.teachers.map(t => [
    t.teacher_id || t.id || '',
    t.name || '',
    t.contact || t.phone || '',
    t.course || '',
    t.status || 'Active',
    t.salary || '0',
    t.currency || 'PKR',
    t.email || ''
  ]);
  return [headers, ...rows];
}

function formatFeeData() {
  const headers = ["Student Roll No", "Student Name", "Fee Currency", "Monthly Tuition Amount", "Invoice Status", "Due Date", "Tutor Assigned"];
  const rows = erpData.students.map(s => [
    s.roll_no || '',
    s.name || '',
    s.currency || 'PKR',
    s.monthly_fee || '0',
    s.fee_status || 'Pending',
    s.due_date || 'N/A',
    s.teacher_name || ''
  ]);
  return [headers, ...rows];
}

function formatAttendanceData() {
  const headers = ["Student ID", "Date of Class", "Attendance Status", "Lesson / Verses Covered", "Homework Assigned", "Tutor Wait Time", "Class Duration"];
  const rows = erpData.attendance.map(a => [
    a.student_id || '',
    a.date || '',
    a.status || 'Present',
    a.lesson || '',
    a.homework || '',
    a.waited || '0 Min',
    a.duration || '30 Min'
  ]);
  return [headers, ...rows];
}
</script>
