-- Al Foz Islamic Institute ERP Queries
-- Standard report generation templates

-- 1. Monthly attendance percentage report
-- SELECT student_id, COUNT(*) as total_days, SUM(CASE WHEN status='Present' THEN 1 ELSE 0 END) as present_days FROM attendance GROUP BY student_id;

-- 2. Total fee collection report
-- SELECT SUM(amount) FROM fees WHERE status='Paid';
