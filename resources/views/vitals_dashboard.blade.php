<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMC QMS - Vital Signs Station</title>
    <script src="https://jsdelivr.net"></script>
    <script src="https://jsdelivr.net"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #0f172a; color: #f8fafc; margin: 20px; }
        .dashboard-container { max-width: 800px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 16px; border: 2px solid #334155; }
        h1 { color: #38bdf8; border-bottom: 2px solid #334155; padding-bottom: 10px; }
        .station-config { background: #0f172a; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px; }
        .control-panel { margin-bottom: 30px; text-align: center; }
        .btn { font-size: 1.2rem; font-weight: bold; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; color: white; transition: background 0.2s; }
        .btn-call { background: #0284c7; width: 100%; }
        .btn-call:hover { background: #0369a1; }
        .btn-submit { background: #10b981; width: 100%; margin-top: 15px; }
        .btn-submit:hover { background: #059669; }
        .patient-box { background: #0f172a; padding: 25px; border-radius: 12px; border: 2px dashed #0284c7; margin-top: 20px; }
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; text-align: left; }
        label { font-weight: bold; margin-bottom: 5px; color: #94a3b8; }
        input { font-size: 1.1rem; padding: 10px; border-radius: 6px; border: 1px solid #475569; background: #1e293b; color: white; }
    </style>
</head>
<body>
    <div id="app" class="dashboard-container">
        <h1>🏥 Vital Signs Assessment Desk</h1>
        
        <!-- Station Configuration -->
        <div class="station-config">
            <label>Assigned Booth/Station Number:</label>
            <input type="number" v-model="counterNumber" style="width: 80px;" min="1">
        </div>

        <!-- Call Patient Control -->
        <div class="control-panel">
            <button v-if="!currentPatient" class="btn btn-call" @click="callNextPatient">🔔 CALL NEXT WAITING PATIENT</button>
        </div>

        <!-- Active Patient Work Area -->
        <div v-if="currentPatient" class="patient-box">
            <h2>Serving Ticket: <span style="color: #38bdf8;">@{{ currentPatient.ticket_number }}</span></h2>
            <p>Classification: <strong>@{{ currentPatient.patient_type.toUpperCase() }}</strong></p>
            <hr style="border-color: #334155;">
            
            <h3>Record Vital Signs Parameters:</h3>
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Systolic BP (mmHg)</label>
                    <input type="text" v-model="vitals.systolic_bp" placeholder="e.g., 120">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Diastolic BP (mmHg)</label>
                    <input type="text" v-model="vitals.diastolic_bp" placeholder="e.g., 80">
                </div>
            </div>
            <div class="form-group">
                <label>Body Temperature (°C)</label>
                <input type="text" v-model="vitals.temperature" placeholder="e.g., 36.5">
            </div>

            <button class="btn btn-submit" @click="saveAndRoute">✅ SAVE & ROUTE TO FRONT DESK REGISTRATION</button>
        </div>
        <p v-else-if="message" style="text-align: center; color: #94a3b8;">@{{ message }}</p>
    </div>

    <script>
        const { createApp, ref } = Vue;

        createApp({
            setup() {
                const counterNumber = ref(1);
                const currentPatient = ref(null);
                const message = ref('');
                const vitals = ref({ systolic_bp: '', diastolic_bp: '', temperature: '' });

                const callNextPatient = () => {
                    axios.post(`/queue/vitals/next/${counterNumber.value}`)
                        .then(response => {
                            if (response.data.status === 'success') {
                                currentPatient.value = response.data.ticket;
                                message.value = '';
                            } else {
                                currentPatient.value = null;
                                message.value = response.data.message;
                            }
                        });
                };

                const saveAndRoute = () => {
                    axios.post(`/queue/vitals/complete/${currentPatient.value.id}`, vitals.value)
                        .then(response => {
                            if (response.data.status === 'success') {
                                alert('Vitals saved successfully! Patient has been passed to Front Desk Registration.');
                                currentPatient.value = null;
                                vitals.value = { systolic_bp: '', diastolic_bp: '', temperature: '' };
                            }
                        });
                };

                return { counterNumber, currentPatient, message, vitals, callNextPatient, saveAndRoute };
            }
        }).mount('#app');
    </script>
</body>
</html>
