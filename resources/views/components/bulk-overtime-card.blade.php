<!-- Bulk Overtime Quick Action Card for Dashboard -->
<div class="bulk-ot-card" onclick="openBulkOvertimeModal()">
  <div class="bulk-ot-header">
    <div class="bulk-ot-icon">schedule</div>
  </div>
  <div class="bulk-ot-body">
    <h3 class="bulk-ot-title">ساعات السهر الجماعية</h3>
    <p class="bulk-ot-desc">تطبيق ساعات السهر على جميع العمال دفعة واحدة</p>
    <button type="button" class="bulk-ot-btn" onclick="openBulkOvertimeModal()">
      <span class="material-symbols-outlined">add</span>
      إضافة جماعي
    </button>
  </div>
</div>

<style>
.bulk-ot-card {
  background: #fff;
  border-radius: 16px;
  border: 1px solid #e5e5e0;
  overflow: hidden;
  transition: all 0.3s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  box-shadow: 0 1px 3px rgba(26, 28, 25, 0.05);
  cursor: pointer;
}

.bulk-ot-card:hover {
  border-color: #1D9E75;
  box-shadow: 0 4px 12px rgba(29, 158, 117, 0.12);
  transform: translateY(-2px);
}

.bulk-ot-header {
  background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bulk-ot-icon {
  font-family: 'Material Symbols Outlined';
  font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
  font-size: 32px;
  color: #fff;
  display: inline-block;
}

.bulk-ot-body {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.bulk-ot-title {
  font-size: 15px;
  font-weight: 700;
  color: #1a1c19;
  margin-bottom: 6px;
}

.bulk-ot-desc {
  font-size: 12px;
  color: #888;
  margin-bottom: 14px;
  line-height: 1.5;
  flex: 1;
}

.bulk-ot-btn {
  background: linear-gradient(135deg, #0d631b 0%, #1D9E75 100%);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-family: 'Tajawal', sans-serif;
}

.bulk-ot-btn:hover {
  box-shadow: 0 4px 10px rgba(13, 99, 27, 0.25);
  transform: scale(1.02);
}

.bulk-ot-btn:active {
  transform: scale(0.98);
}

.bulk-ot-btn .ms {
  font-size: 18px;
}

/* ============ TABLET/MOBILE (max-width: 768px) ============ */
@media (max-width: 768px) {
  .bulk-ot-card {
    border-radius: 12px;
  }
  
  .bulk-ot-header {
    padding: 16px;
  }
  
  .bulk-ot-icon {
    font-size: 28px;
  }
  
  .bulk-ot-body {
    padding: 16px;
  }
  
  .bulk-ot-title {
    font-size: 14px;
    margin-bottom: 4px;
  }
  
  .bulk-ot-desc {
    font-size: 11px;
    margin-bottom: 12px;
  }
  
  .bulk-ot-btn {
    padding: 9px 12px;
    font-size: 11px;
    gap: 4px;
  }
  
  .bulk-ot-btn .ms {
    font-size: 16px;
  }
}

/* ============ SMALL PHONE (max-width: 480px) ============ */
@media (max-width: 480px) {
  .bulk-ot-card {
    border-radius: 10px;
  }
  
  .bulk-ot-header {
    padding: 14px;
  }
  
  .bulk-ot-icon {
    font-size: 26px;
  }
  
  .bulk-ot-body {
    padding: 14px;
  }
  
  .bulk-ot-title {
    font-size: 13px;
  }
  
  .bulk-ot-desc {
    font-size: 10px;
  }
  
  .bulk-ot-btn {
    padding: 8px 10px;
    font-size: 10px;
  }
  
  .bulk-ot-btn:hover {
    transform: scale(1.01);
  }
}

/* ============ VERY SMALL PHONE (max-width: 360px) ============ */
@media (max-width: 360px) {
  .bulk-ot-card {
    border-radius: 8px;
  }
  
  .bulk-ot-header {
    padding: 12px;
  }
  
  .bulk-ot-icon {
    font-size: 24px;
  }
  
  .bulk-ot-body {
    padding: 12px;
  }
  
  .bulk-ot-title {
    font-size: 12px;
    margin-bottom: 2px;
  }
  
  .bulk-ot-desc {
    font-size: 9px;
    margin-bottom: 10px;
  }
  
  .bulk-ot-btn {
    padding: 7px 8px;
    font-size: 9px;
  }
}
</style>
