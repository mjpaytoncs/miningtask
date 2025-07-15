# miningtask

A jsPsych-based behavioral task for sequential information sampling and decision-making.  
Participants land on a virtual asteroid (with gold either at the surface or in the core), can scan as many times as they like (at a small cost per scan) to get noisy information, then choose to dig at the core or surface. Digging correctly yields a reward; digging incorrectly results in a penalty. We log choices, reaction times, number of scans, confidence ratings, and final earnings.

---

## Repository Structure

miningtask/
├── index.html ← Main experiment file (jsPsych 6.3.0)
├── README.md ← This file
└── data/ ← (Optional) folder for saved CSVs and logs


---

##  Installation & Setup

1. **Clone the repo**  
   ```bash
   git clone https://github.com/yourusername/miningtask.git
   cd miningtask

Serve locally
jsPsych experiments must run over HTTP. You can use a simple server:

# Python 3
python3 -m http.server 8000

# or Node
npx http-server -c-1

Then point your browser at http://localhost:8000/index.html.

Dependencies

jsPsych 6.3.0 (loaded via CDN in index.html)

A modern browser (Chrome/Firefox/Safari)

Usage
Open index.html in your browser (via your local server).

Participants see instructions, then complete N_BLOCKS × TRIALS_PER_BLOCK trials.

After each “dig” they provide a confidence rating on a slider.

At the end, their final gold balance is displayed and data are exported as CSV.

 Configuration
All key parameters live at the top of mining.html:

// ==== PARAMETERS ====
const N_BLOCKS         = 3;     // number of blocks
const TRIALS_PER_BLOCK = 8;     // trials per block
const STARTING_MONEY   = 100;   // initial gold balance
const NOISE            = 0.65;  // P(scan is correct)
const SCAN_COST        = 1;     // gold lost per scan
const REWARD           = 50;    // gold gained for correct dig
const PENALTY          = 100;   // gold lost for incorrect dig

(You can obviously change these to run different, or varied conditions)

Data Output
Each time the experiment finishes, the data are available in the browser console or can be saved automatically:

Fields recorded per dig trial

trial_part = "confidence" (for slider trials)

response_time (in ms)

dug = "core" or "surface"

correct = true/false

delta = change in gold (e.g. +50 or -100)

scanCount = total number of scans this trial

confidence = 0–100 slider rating

currentMoney = balance after each action (logged on every trial)

📝 Task Flow
Block reminder screen (shows current parameters).

Scan-or-dig loop

“🔍 Scan” deducts SCAN_COST and shows a noisy observation.

“⛏ Dig Core” / “🌐 Dig Surface” ends the loop, applies REWARD or −PENALTY.

Confidence slider (0–100) before feedback.

Feedback screen (“You found the gold!” or “Nothing there!” + updated balance).

Next trial (or next block), until all are complete.

🛠️ Customization & Extensions
Add new data fields

Adjust timing (e.g. trial_duration on feedback screens)

Style/UI tweaks in the <style> block at the top

Automate data saving to your server via AJAX

🤝 Contributing
Fork this repo

Create a branch (git checkout -b feature/my-change)

Commit your changes (git commit -am "Add some feature")

Push to the branch (git push origin feature/my-change)

Open a Pull Request

📄 License
This code is released under the MIT License. See LICENSE for details.

⛏ ⛏ ⛏ ⛏ ⛏ ⛏ ⛏ Happy mining!⛏ ⛏ ⛏ ⛏ ⛏ ⛏ 