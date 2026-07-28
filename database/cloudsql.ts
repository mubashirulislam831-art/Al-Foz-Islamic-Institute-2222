import { Pool } from "pg";
import * as fs from "fs";
import * as path from "path";

let pgPool: Pool | null = null;

// Initialize PostgreSQL pool if environment variables are set (active on Cloud Run)
if (process.env.SQL_HOST) {
  console.log(`[CloudSQL] Detected Cloud SQL host: ${process.env.SQL_HOST}. Initializing connection pool...`);
  pgPool = new Pool({
    host: process.env.SQL_HOST,
    user: process.env.SQL_USER,
    password: process.env.SQL_PASSWORD,
    database: process.env.SQL_DB_NAME,
    connectionTimeoutMillis: 10000,
    idleTimeoutMillis: 30000,
    max: 15,
  });

  pgPool.on("error", (err) => {
    console.error("[CloudSQL] Unexpected error on idle client:", err);
  });
} else {
  console.log("[CloudSQL] SQL_HOST environment variable is not defined. Cloud SQL database is inactive in development.");
}

export function isCloudSQLActive(): boolean {
  return pgPool !== null;
}

/**
 * Ensures all ERP tables are initialized and created in the PostgreSQL database.
 */
export async function initCloudSQL(): Promise<void> {
  if (!pgPool) return;

  const client = await pgPool.connect();
  try {
    console.log("[CloudSQL] Checking and initializing database tables...");

    await client.query("BEGIN;");

    // 1. users table
    await client.query(`
      CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL,
        status VARCHAR(50) DEFAULT 'Active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `);

    // 2. admins table
    await client.query(`
      CREATE TABLE IF NOT EXISTS admins (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        employee_code VARCHAR(50) UNIQUE NOT NULL,
        branch_assigned VARCHAR(100) DEFAULT 'Main Islamabad',
        whatsapp VARCHAR(50) DEFAULT ''
      );
    `);

    // 3. teachers table
    await client.query(`
      CREATE TABLE IF NOT EXISTS teachers (
        id SERIAL PRIMARY KEY,
        user_id INT DEFAULT NULL,
        employee_id VARCHAR(50) UNIQUE NOT NULL,
        name VARCHAR(255) DEFAULT '',
        father_name VARCHAR(255) DEFAULT '',
        gender VARCHAR(50) NOT NULL,
        dob DATE DEFAULT NULL,
        whatsapp VARCHAR(50) NOT NULL,
        country VARCHAR(100) DEFAULT 'Pakistan',
        city VARCHAR(100) DEFAULT 'Lahore',
        timezone VARCHAR(50) DEFAULT 'PKT',
        qualification VARCHAR(255) DEFAULT 'Shahadat-ul-Alimia',
        experience VARCHAR(100) DEFAULT '5 Years',
        specialization VARCHAR(255) DEFAULT 'Tajweed & Quran Hifz',
        joining_date DATE NOT NULL,
        salary DECIMAL(10,2) NOT NULL DEFAULT 25000.00,
        bank_name VARCHAR(255) DEFAULT 'Meezan Bank',
        account_title VARCHAR(255) DEFAULT '',
        account_number VARCHAR(100) DEFAULT '',
        iban VARCHAR(100) DEFAULT '',
        status VARCHAR(50) DEFAULT 'Active',
        assigned_students INT DEFAULT 0,
        portal_email VARCHAR(255) DEFAULT '',
        portal_password VARCHAR(255) DEFAULT ''
      );
    `);

    // 4. parents table
    await client.query(`
      CREATE TABLE IF NOT EXISTS parents (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        father_name VARCHAR(255) DEFAULT '',
        email VARCHAR(255) DEFAULT '',
        whatsapp VARCHAR(50) NOT NULL,
        country VARCHAR(100) DEFAULT 'Pakistan',
        timezone VARCHAR(50) DEFAULT 'PKT',
        status VARCHAR(50) DEFAULT 'Active',
        relation VARCHAR(100) DEFAULT 'Father',
        portal_email VARCHAR(255) DEFAULT '',
        portal_password VARCHAR(255) DEFAULT '',
        students JSONB DEFAULT '[]'::jsonb
      );
    `);

    // 5. students table
    await client.query(`
      CREATE TABLE IF NOT EXISTS students (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        parent_id INT DEFAULT NULL,
        roll_no VARCHAR(50) UNIQUE NOT NULL,
        name VARCHAR(255) DEFAULT '',
        father_name VARCHAR(255) DEFAULT '',
        email VARCHAR(255) DEFAULT '',
        gender VARCHAR(50) NOT NULL,
        dob DATE NOT NULL,
        whatsapp VARCHAR(50) NOT NULL,
        country VARCHAR(100) DEFAULT 'Pakistan',
        city VARCHAR(100) DEFAULT 'Lahore',
        timezone VARCHAR(50) DEFAULT 'PKT',
        currency VARCHAR(10) DEFAULT 'PKR',
        admission_type VARCHAR(100) DEFAULT 'Self',
        custom_course VARCHAR(255) DEFAULT '',
        teacher_id VARCHAR(50) DEFAULT '',
        teacher_name VARCHAR(255) DEFAULT '',
        class_days JSONB DEFAULT '[]'::jsonb,
        trial_days JSONB DEFAULT '[]'::jsonb,
        status VARCHAR(50) DEFAULT 'Active',
        deactivated_status VARCHAR(10) DEFAULT 'No',
        attendance_status VARCHAR(50) DEFAULT 'Present',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        portal_email VARCHAR(255) DEFAULT '',
        portal_password VARCHAR(255) DEFAULT '',
        student_id VARCHAR(50) DEFAULT '',
        course VARCHAR(255) DEFAULT '',
        shift VARCHAR(50) DEFAULT '',
        class_time VARCHAR(50) DEFAULT '',
        fee DECIMAL(10,2) DEFAULT 0.00,
        referred_by VARCHAR(255) DEFAULT '',
        comments TEXT DEFAULT '',
        files JSONB DEFAULT '[]'::jsonb
      );
    `);

    // 6. attendance table
    await client.query(`
      CREATE TABLE IF NOT EXISTS attendance (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        notes TEXT DEFAULT '',
        date VARCHAR(50) NOT NULL,
        marked_by VARCHAR(100) DEFAULT '',
        marked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `);

    // 7. classes table
    await client.query(`
      CREATE TABLE IF NOT EXISTS classes (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        teacher_id VARCHAR(50) NOT NULL,
        class_day VARCHAR(50) NOT NULL,
        class_time VARCHAR(50) NOT NULL,
        duration INT NOT NULL,
        status VARCHAR(50) DEFAULT 'Active'
      );
    `);

    // 8. rescheduled_classes table
    await client.query(`
      CREATE TABLE IF NOT EXISTS rescheduled_classes (
        id SERIAL PRIMARY KEY,
        class_id INT NOT NULL,
        original_date VARCHAR(50) NOT NULL,
        new_date VARCHAR(50) NOT NULL,
        new_time VARCHAR(50) NOT NULL,
        status VARCHAR(50) DEFAULT 'Pending',
        reason TEXT DEFAULT '',
        requested_by VARCHAR(100) DEFAULT ''
      );
    `);

    // 9. timers table
    await client.query(`
      CREATE TABLE IF NOT EXISTS timers (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        start_time TIMESTAMP DEFAULT NULL,
        end_time TIMESTAMP DEFAULT NULL,
        duration INT DEFAULT 0,
        task_description TEXT DEFAULT ''
      );
    `);

    // 10. exams table
    await client.query(`
      CREATE TABLE IF NOT EXISTS exams (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        exam_name VARCHAR(100) NOT NULL,
        subject VARCHAR(100) NOT NULL,
        max_marks INT NOT NULL,
        obtained_marks INT NOT NULL,
        exam_date VARCHAR(50) NOT NULL,
        status VARCHAR(50) DEFAULT 'Completed'
      );
    `);

    // 11. homework table
    await client.query(`
      CREATE TABLE IF NOT EXISTS homework (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT DEFAULT '',
        due_date VARCHAR(50) NOT NULL,
        status VARCHAR(50) DEFAULT 'Assigned',
        assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `);

    // 12. fees table
    await client.query(`
      CREATE TABLE IF NOT EXISTS fees (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        due_date VARCHAR(50) NOT NULL,
        status VARCHAR(50) DEFAULT 'Unpaid',
        paid_date VARCHAR(50) DEFAULT NULL,
        payment_method VARCHAR(100) DEFAULT '',
        invoice_no VARCHAR(100) DEFAULT ''
      );
    `);

    // 13. salary table
    await client.query(`
      CREATE TABLE IF NOT EXISTS salary (
        id SERIAL PRIMARY KEY,
        teacher_id VARCHAR(50) NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        month VARCHAR(50) NOT NULL,
        year INT NOT NULL,
        status VARCHAR(50) DEFAULT 'Unpaid',
        paid_date VARCHAR(50) DEFAULT NULL,
        transaction_id VARCHAR(100) DEFAULT '',
        remarks TEXT DEFAULT '',
        bonus DECIMAL(10,2) DEFAULT 0.00,
        deduction DECIMAL(10,2) DEFAULT 0.00,
        payment_method VARCHAR(100) DEFAULT ''
      );
    `);

    // 14. formulas table
    await client.query(`
      CREATE TABLE IF NOT EXISTS formulas (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        rule_type VARCHAR(100) NOT NULL,
        multiplier DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) DEFAULT 'Active'
      );
    `);

    // 15. progress_reports table
    await client.query(`
      CREATE TABLE IF NOT EXISTS progress_reports (
        id SERIAL PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        report_period VARCHAR(100) NOT NULL,
        grade VARCHAR(50) DEFAULT '',
        remarks TEXT DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );
    `);

    // 16. notifications table
    await client.query(`
      CREATE TABLE IF NOT EXISTS notifications (
        id SERIAL PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        recipient_group VARCHAR(100) NOT NULL,
        channels VARCHAR(255) NOT NULL,
        status VARCHAR(50) DEFAULT 'Sent',
        date_sent VARCHAR(50) NOT NULL
      );
    `);

    // 17. activity_logs table
    await client.query(`
      CREATE TABLE IF NOT EXISTS activity_logs (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        action VARCHAR(255) NOT NULL,
        details TEXT NOT NULL,
        timestamp VARCHAR(100) NOT NULL
      );
    `);

    // 18. login_history table
    await client.query(`
      CREATE TABLE IF NOT EXISTS login_history (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        ip_address VARCHAR(100) DEFAULT '',
        device VARCHAR(255) DEFAULT '',
        status VARCHAR(50) DEFAULT 'Success',
        timestamp VARCHAR(100) NOT NULL
      );
    `);

    // Safe ADD COLUMN IF NOT EXISTS migration commands to ensure backward compatibility
    // and safe incremental schema updates without dropping or recreating tables
    await client.query(`
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS bank_name VARCHAR(255) DEFAULT 'Meezan Bank';
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS account_title VARCHAR(255) DEFAULT '';
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS account_number VARCHAR(100) DEFAULT '';
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS iban VARCHAR(100) DEFAULT '';
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS portal_email VARCHAR(255) DEFAULT '';
      ALTER TABLE teachers ADD COLUMN IF NOT EXISTS portal_password VARCHAR(255) DEFAULT '';

      ALTER TABLE students ADD COLUMN IF NOT EXISTS portal_email VARCHAR(255) DEFAULT '';
      ALTER TABLE students ADD COLUMN IF NOT EXISTS portal_password VARCHAR(255) DEFAULT '';
      ALTER TABLE students ADD COLUMN IF NOT EXISTS files JSONB DEFAULT '[]'::jsonb;
      ALTER TABLE students ADD COLUMN IF NOT EXISTS comments TEXT DEFAULT '';
      ALTER TABLE students ADD COLUMN IF NOT EXISTS referred_by VARCHAR(255) DEFAULT '';
      ALTER TABLE students ADD COLUMN IF NOT EXISTS fee DECIMAL(10,2) DEFAULT 0.00;

      ALTER TABLE parents ADD COLUMN IF NOT EXISTS portal_email VARCHAR(255) DEFAULT '';
      ALTER TABLE parents ADD COLUMN IF NOT EXISTS portal_password VARCHAR(255) DEFAULT '';
      ALTER TABLE parents ADD COLUMN IF NOT EXISTS students JSONB DEFAULT '[]'::jsonb;

      ALTER TABLE attendance ADD COLUMN IF NOT EXISTS lesson VARCHAR(255) DEFAULT '-';
      ALTER TABLE attendance ADD COLUMN IF NOT EXISTS homework VARCHAR(255) DEFAULT '-';
      ALTER TABLE attendance ADD COLUMN IF NOT EXISTS remarks VARCHAR(255) DEFAULT '-';
      ALTER TABLE attendance ADD COLUMN IF NOT EXISTS waited VARCHAR(50) DEFAULT '0 Min';
      ALTER TABLE attendance ADD COLUMN IF NOT EXISTS duration VARCHAR(50) DEFAULT '30 Min';
    `);

    await client.query("COMMIT;");
    console.log("[CloudSQL] Database tables check and initialization completed successfully.");
  } catch (error) {
    await client.query("ROLLBACK;");
    console.error("[CloudSQL] Failed to initialize database tables:", error);
    throw error;
  } finally {
    client.release();
  }
}

/**
 * Loads entire dynamic state from PostgreSQL.
 */
export async function loadStateFromCloudSQL(): Promise<any> {
  if (!pgPool) return null;

  const result: any = {};
  const tables = [
    { name: "users", key: "users" },
    { name: "admins", key: "admins" },
    { name: "teachers", key: "teachers" },
    { name: "parents", key: "parents" },
    { name: "students", key: "students" },
    { name: "attendance", key: "attendance" },
    { name: "classes", key: "classes" },
    { name: "rescheduled_classes", key: "rescheduled_classes" },
    { name: "timers", key: "timers" },
    { name: "exams", key: "exams" },
    { name: "homework", key: "homework" },
    { name: "fees", key: "fees" },
    { name: "salary", key: "salary" },
    { name: "formulas", key: "formulas" },
    { name: "progress_reports", key: "progress_reports" },
    { name: "notifications", key: "notifications" },
    { name: "activity_logs", key: "activity_logs" },
    { name: "login_history", key: "login_history" },
  ];

  for (const t of tables) {
    try {
      const queryResult = await pgPool.query(`SELECT * FROM ${t.name} ORDER BY id ASC`);
      result[t.key] = queryResult.rows.map(row => {
        // Deserialize JSONB fields
        if (t.name === "parents" && row.students) {
          try { row.students = typeof row.students === "string" ? JSON.parse(row.students) : row.students; } catch (e) {}
        }
        if (t.name === "students") {
          if (row.class_days) {
            try { row.class_days = typeof row.class_days === "string" ? JSON.parse(row.class_days) : row.class_days; } catch (e) {}
          }
          if (row.trial_days) {
            try { row.trial_days = typeof row.trial_days === "string" ? JSON.parse(row.trial_days) : row.trial_days; } catch (e) {}
          }
          if (row.files) {
            try { row.files = typeof row.files === "string" ? JSON.parse(row.files) : row.files; } catch (e) {}
          }
        }
        return row;
      });
    } catch (e) {
      console.error(`[CloudSQL] Error reading table ${t.name}:`, e);
      result[t.key] = [];
    }
  }

  return result;
}

/**
 * Saves/Upserts entire state to PostgreSQL.
 */
export async function saveStateToCloudSQL(state: any): Promise<void> {
  if (!pgPool) return;

  const client = await pgPool.connect();
  try {
    await client.query("BEGIN;");

    // Helper function to safe-format dates
    const formatDate = (d: any) => {
      if (!d) return null;
      try {
        const parsed = new Date(d);
        return isNaN(parsed.getTime()) ? null : parsed.toISOString().split("T")[0];
      } catch (e) {
        return null;
      }
    };

    // 1. users
    if (state.users && Array.isArray(state.users)) {
      for (const item of state.users) {
        await client.query(`
          INSERT INTO users (id, name, email, password, role, status)
          VALUES ($1, $2, $3, $4, $5, $6)
          ON CONFLICT (id) DO UPDATE SET
            name = EXCLUDED.name,
            email = EXCLUDED.email,
            password = EXCLUDED.password,
            role = EXCLUDED.role,
            status = EXCLUDED.status;
        `, [item.id, item.name, item.email, item.password, item.role, item.status || "Active"]);
      }
    }

    // 2. admins
    if (state.admins && Array.isArray(state.admins)) {
      for (const item of state.admins) {
        await client.query(`
          INSERT INTO admins (id, user_id, employee_code, branch_assigned, whatsapp)
          VALUES ($1, $2, $3, $4, $5)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            employee_code = EXCLUDED.employee_code,
            branch_assigned = EXCLUDED.branch_assigned,
            whatsapp = EXCLUDED.whatsapp;
        `, [item.id, item.user_id, item.employee_code, item.branch_assigned, item.whatsapp || ""]);
      }
    }

    // 3. teachers
    if (state.teachers && Array.isArray(state.teachers)) {
      for (const item of state.teachers) {
        await client.query(`
          INSERT INTO teachers (
            id, user_id, employee_id, name, father_name, gender, dob, whatsapp,
            country, city, timezone, qualification, experience, specialization,
            joining_date, salary, bank_name, account_title, account_number, iban,
            status, assigned_students, portal_email, portal_password
          )
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23, $24)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            employee_id = EXCLUDED.employee_id,
            name = EXCLUDED.name,
            father_name = EXCLUDED.father_name,
            gender = EXCLUDED.gender,
            dob = EXCLUDED.dob,
            whatsapp = EXCLUDED.whatsapp,
            country = EXCLUDED.country,
            city = EXCLUDED.city,
            timezone = EXCLUDED.timezone,
            qualification = EXCLUDED.qualification,
            experience = EXCLUDED.experience,
            specialization = EXCLUDED.specialization,
            joining_date = EXCLUDED.joining_date,
            salary = EXCLUDED.salary,
            bank_name = EXCLUDED.bank_name,
            account_title = EXCLUDED.account_title,
            account_number = EXCLUDED.account_number,
            iban = EXCLUDED.iban,
            status = EXCLUDED.status,
            assigned_students = EXCLUDED.assigned_students,
            portal_email = EXCLUDED.portal_email,
            portal_password = EXCLUDED.portal_password;
        `, [
          item.id, item.user_id || 1, item.employee_id, item.name || "", item.father_name || "",
          item.gender || "Male", formatDate(item.joining_date), item.whatsapp || "",
          item.country || "Pakistan", item.city || "Lahore", item.timezone || "PKT",
          item.qualification || "Shahadat-ul-Alimia", item.experience || "5 Years",
          item.specialization || "Tajweed & Quran Hifz", formatDate(item.joining_date) || "2026-06-01",
          item.salary || 25000.00, item.bank_name || "Meezan Bank", item.account_title || "",
          item.account_number || "", item.iban || "", item.status || "Active",
          item.assigned_students || 0, item.portal_email || "", item.portal_password || ""
        ]);
      }
    }

    // 4. parents
    if (state.parents && Array.isArray(state.parents)) {
      for (const item of state.parents) {
        await client.query(`
          INSERT INTO parents (id, user_id, father_name, email, whatsapp, country, timezone, status, relation, portal_email, portal_password, students)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            father_name = EXCLUDED.father_name,
            email = EXCLUDED.email,
            whatsapp = EXCLUDED.whatsapp,
            country = EXCLUDED.country,
            timezone = EXCLUDED.timezone,
            status = EXCLUDED.status,
            relation = EXCLUDED.relation,
            portal_email = EXCLUDED.portal_email,
            portal_password = EXCLUDED.portal_password,
            students = EXCLUDED.students;
        `, [
          item.id, item.user_id || 1, item.father_name || "", item.email || "",
          item.whatsapp || "", item.country || "Pakistan", item.timezone || "PKT",
          item.status || "Active", item.relation || "Father", item.portal_email || "",
          item.portal_password || "", JSON.stringify(item.students || [])
        ]);
      }
    }

    // 5. students
    if (state.students && Array.isArray(state.students)) {
      for (const item of state.students) {
        await client.query(`
          INSERT INTO students (
            id, user_id, parent_id, roll_no, name, father_name, email, gender, dob, whatsapp,
            country, city, timezone, currency, admission_type, custom_course, teacher_id,
            teacher_name, class_days, trial_days, status, deactivated_status, attendance_status,
            portal_email, portal_password, student_id, course, shift, class_time, fee,
            referred_by, comments, files
          )
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16, $17, $18, $19, $20, $21, $22, $23, $24, $25, $26, $27, $28, $29, $30, $31, $32, $33)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            parent_id = EXCLUDED.parent_id,
            roll_no = EXCLUDED.roll_no,
            name = EXCLUDED.name,
            father_name = EXCLUDED.father_name,
            email = EXCLUDED.email,
            gender = EXCLUDED.gender,
            dob = EXCLUDED.dob,
            whatsapp = EXCLUDED.whatsapp,
            country = EXCLUDED.country,
            city = EXCLUDED.city,
            timezone = EXCLUDED.timezone,
            currency = EXCLUDED.currency,
            admission_type = EXCLUDED.admission_type,
            custom_course = EXCLUDED.custom_course,
            teacher_id = EXCLUDED.teacher_id,
            teacher_name = EXCLUDED.teacher_name,
            class_days = EXCLUDED.class_days,
            trial_days = EXCLUDED.trial_days,
            status = EXCLUDED.status,
            deactivated_status = EXCLUDED.deactivated_status,
            attendance_status = EXCLUDED.attendance_status,
            portal_email = EXCLUDED.portal_email,
            portal_password = EXCLUDED.portal_password,
            student_id = EXCLUDED.student_id,
            course = EXCLUDED.course,
            shift = EXCLUDED.shift,
            class_time = EXCLUDED.class_time,
            fee = EXCLUDED.fee,
            referred_by = EXCLUDED.referred_by,
            comments = EXCLUDED.comments,
            files = EXCLUDED.files;
        `, [
          item.id, item.user_id || 1, item.parent_id || null, item.roll_no, item.name || "",
          item.father_name || "", item.email || "", item.gender || "Male", formatDate(item.dob) || "2015-01-01",
          item.whatsapp || "", item.country || "Pakistan", item.city || "Lahore", item.timezone || "PKT",
          item.currency || "PKR", item.admission_type || "Self", item.custom_course || "",
          item.teacher_id || "", item.teacher_name || "", JSON.stringify(item.class_days || []),
          JSON.stringify(item.trial_days || []), item.status || "Active", item.deactivated_status || "No",
          item.attendance_status || "Present", item.portal_email || "", item.portal_password || "",
          item.student_id || "", item.course || "", item.shift || "", item.class_time || "",
          item.fee || 0.00, item.referred_by || "", item.comments || "", JSON.stringify(item.files || [])
        ]);
      }
    }

    // 6. attendance
    if (state.attendance && Array.isArray(state.attendance)) {
      for (const item of state.attendance) {
        await client.query(`
          INSERT INTO attendance (id, student_id, status, notes, date, marked_by, lesson, homework, remarks, waited, duration)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            status = EXCLUDED.status,
            notes = EXCLUDED.notes,
            date = EXCLUDED.date,
            marked_by = EXCLUDED.marked_by,
            lesson = EXCLUDED.lesson,
            homework = EXCLUDED.homework,
            remarks = EXCLUDED.remarks,
            waited = EXCLUDED.waited,
            duration = EXCLUDED.duration;
        `, [
          item.id,
          item.student_id,
          item.status,
          item.notes || "",
          item.date,
          item.marked_by || "",
          item.lesson || "-",
          item.homework || "-",
          item.remarks || "-",
          item.waited || "0 Min",
          item.duration || "30 Min"
        ]);
      }
    }

    // 7. classes
    if (state.classes && Array.isArray(state.classes)) {
      for (const item of state.classes) {
        await client.query(`
          INSERT INTO classes (id, student_id, teacher_id, class_day, class_time, duration, status)
          VALUES ($1, $2, $3, $4, $5, $6, $7)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            teacher_id = EXCLUDED.teacher_id,
            class_day = EXCLUDED.class_day,
            class_time = EXCLUDED.class_time,
            duration = EXCLUDED.duration,
            status = EXCLUDED.status;
        `, [item.id, item.student_id, item.teacher_id, item.class_day, item.class_time, item.duration || 30, item.status || "Active"]);
      }
    }

    // 8. rescheduled_classes
    if (state.rescheduled_classes && Array.isArray(state.rescheduled_classes)) {
      for (const item of state.rescheduled_classes) {
        await client.query(`
          INSERT INTO rescheduled_classes (id, class_id, original_date, new_date, new_time, status, reason, requested_by)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
          ON CONFLICT (id) DO UPDATE SET
            class_id = EXCLUDED.class_id,
            original_date = EXCLUDED.original_date,
            new_date = EXCLUDED.new_date,
            new_time = EXCLUDED.new_time,
            status = EXCLUDED.status,
            reason = EXCLUDED.reason,
            requested_by = EXCLUDED.requested_by;
        `, [item.id, item.class_id, item.original_date, item.new_date, item.new_time, item.status || "Pending", item.reason || "", item.requested_by || ""]);
      }
    }

    // 9. timers
    if (state.timers && Array.isArray(state.timers)) {
      for (const item of state.timers) {
        await client.query(`
          INSERT INTO timers (id, user_id, start_time, end_time, duration, task_description)
          VALUES ($1, $2, $3, $4, $5, $6)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            start_time = EXCLUDED.start_time,
            end_time = EXCLUDED.end_time,
            duration = EXCLUDED.duration,
            task_description = EXCLUDED.task_description;
        `, [item.id, item.user_id, item.start_time || null, item.end_time || null, item.duration || 0, item.task_description || ""]);
      }
    }

    // 10. exams
    if (state.exams && Array.isArray(state.exams)) {
      for (const item of state.exams) {
        await client.query(`
          INSERT INTO exams (id, student_id, exam_name, subject, max_marks, obtained_marks, exam_date, status)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            exam_name = EXCLUDED.exam_name,
            subject = EXCLUDED.subject,
            max_marks = EXCLUDED.max_marks,
            obtained_marks = EXCLUDED.obtained_marks,
            exam_date = EXCLUDED.exam_date,
            status = EXCLUDED.status;
        `, [item.id, item.student_id, item.exam_name, item.subject, item.max_marks || 100, item.obtained_marks || 0, item.exam_date, item.status || "Completed"]);
      }
    }

    // 11. homework
    if (state.homework && Array.isArray(state.homework)) {
      for (const item of state.homework) {
        await client.query(`
          INSERT INTO homework (id, student_id, title, description, due_date, status)
          VALUES ($1, $2, $3, $4, $5, $6)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            title = EXCLUDED.title,
            description = EXCLUDED.description,
            due_date = EXCLUDED.due_date,
            status = EXCLUDED.status;
        `, [item.id, item.student_id, item.title, item.description || "", item.due_date, item.status || "Assigned"]);
      }
    }

    // 12. fees
    if (state.fees && Array.isArray(state.fees)) {
      for (const item of state.fees) {
        await client.query(`
          INSERT INTO fees (id, student_id, amount, due_date, status, paid_date, payment_method, invoice_no)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            amount = EXCLUDED.amount,
            due_date = EXCLUDED.due_date,
            status = EXCLUDED.status,
            paid_date = EXCLUDED.paid_date,
            payment_method = EXCLUDED.payment_method,
            invoice_no = EXCLUDED.invoice_no;
        `, [item.id, item.student_id, item.amount, item.due_date, item.status || "Unpaid", item.paid_date || null, item.payment_method || "", item.invoice_no || ""]);
      }
    }

    // 13. salary
    if (state.salary && Array.isArray(state.salary)) {
      for (const item of state.salary) {
        await client.query(`
          INSERT INTO salary (id, teacher_id, amount, month, year, status, paid_date, transaction_id, remarks, bonus, deduction, payment_method)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12)
          ON CONFLICT (id) DO UPDATE SET
            teacher_id = EXCLUDED.teacher_id,
            amount = EXCLUDED.amount,
            month = EXCLUDED.month,
            year = EXCLUDED.year,
            status = EXCLUDED.status,
            paid_date = EXCLUDED.paid_date,
            transaction_id = EXCLUDED.transaction_id,
            remarks = EXCLUDED.remarks,
            bonus = EXCLUDED.bonus,
            deduction = EXCLUDED.deduction,
            payment_method = EXCLUDED.payment_method;
        `, [
          item.id, item.teacher_id, item.amount, item.month, item.year || 2026,
          item.status || "Unpaid", item.paid_date || null, item.transaction_id || "",
          item.remarks || "", item.bonus || 0.00, item.deduction || 0.00, item.payment_method || ""
        ]);
      }
    }

    // 14. formulas
    if (state.formulas && Array.isArray(state.formulas)) {
      for (const item of state.formulas) {
        await client.query(`
          INSERT INTO formulas (id, name, rule_type, multiplier, status)
          VALUES ($1, $2, $3, $4, $5)
          ON CONFLICT (id) DO UPDATE SET
            name = EXCLUDED.name,
            rule_type = EXCLUDED.rule_type,
            multiplier = EXCLUDED.multiplier,
            status = EXCLUDED.status;
        `, [item.id, item.name, item.rule_type, item.multiplier, item.status || "Active"]);
      }
    }

    // 15. progress_reports
    if (state.progress_reports && Array.isArray(state.progress_reports)) {
      for (const item of state.progress_reports) {
        await client.query(`
          INSERT INTO progress_reports (id, student_id, report_period, grade, remarks)
          VALUES ($1, $2, $3, $4, $5)
          ON CONFLICT (id) DO UPDATE SET
            student_id = EXCLUDED.student_id,
            report_period = EXCLUDED.report_period,
            grade = EXCLUDED.grade,
            remarks = EXCLUDED.remarks;
        `, [item.id, item.student_id, item.report_period, item.grade || "", item.remarks || ""]);
      }
    }

    // 16. notifications
    if (state.notifications && Array.isArray(state.notifications)) {
      for (const item of state.notifications) {
        await client.query(`
          INSERT INTO notifications (id, title, content, recipient_group, channels, status, date_sent)
          VALUES ($1, $2, $3, $4, $5, $6, $7)
          ON CONFLICT (id) DO UPDATE SET
            title = EXCLUDED.title,
            content = EXCLUDED.content,
            recipient_group = EXCLUDED.recipient_group,
            channels = EXCLUDED.channels,
            status = EXCLUDED.status,
            date_sent = EXCLUDED.date_sent;
        `, [item.id, item.title, item.content, item.recipient_group, item.channels, item.status || "Sent", item.date_sent]);
      }
    }

    // 17. activity_logs
    if (state.activity_logs && Array.isArray(state.activity_logs)) {
      for (const item of state.activity_logs) {
        await client.query(`
          INSERT INTO activity_logs (id, user_id, action, details, timestamp)
          VALUES ($1, $2, $3, $4, $5)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            action = EXCLUDED.action,
            details = EXCLUDED.details,
            timestamp = EXCLUDED.timestamp;
        `, [item.id, item.user_id, item.action, item.details, item.timestamp]);
      }
    }

    // 18. login_history
    if (state.login_history && Array.isArray(state.login_history)) {
      for (const item of state.login_history) {
        await client.query(`
          INSERT INTO login_history (id, user_id, email, ip_address, device, status, timestamp)
          VALUES ($1, $2, $3, $4, $5, $6, $7)
          ON CONFLICT (id) DO UPDATE SET
            user_id = EXCLUDED.user_id,
            email = EXCLUDED.email,
            ip_address = EXCLUDED.ip_address,
            device = EXCLUDED.device,
            status = EXCLUDED.status,
            timestamp = EXCLUDED.timestamp;
        `, [item.id, item.user_id, item.email, item.ip_address || "", item.device || "", item.status || "Success", item.timestamp]);
      }
    }

    // Reset SERIAL sequences so auto-increment works correctly after manual ID inserts
    const serialTables = [
      "users", "admins", "teachers", "parents", "students", "attendance", "classes",
      "rescheduled_classes", "timers", "exams", "homework", "fees", "salary",
      "formulas", "progress_reports", "notifications", "activity_logs", "login_history"
    ];
    for (const tbl of serialTables) {
      try {
        await client.query(`SELECT setval(pg_get_serial_sequence('${tbl}', 'id'), COALESCE(MAX(id), 1)) FROM ${tbl};`);
      } catch (seqErr) {
        // Safe catch in case some tables don't have sequences, though they all should
        console.error(`[CloudSQL] Warning: could not reset sequence for table ${tbl}:`, seqErr);
      }
    }

    await client.query("COMMIT;");
    console.log("[CloudSQL] Successfully saved state to Cloud SQL.");
  } catch (error) {
    await client.query("ROLLBACK;");
    console.error("[CloudSQL] Failed to save state to Cloud SQL:", error);
  } finally {
    client.release();
  }
}
