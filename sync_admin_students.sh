#!/bin/bash
rm -rf ./admin/students/*
cp -R ./superadmin/students/* ./admin/students/
find ./admin/students -type f -name "*.php" -exec sed -i "s/require_role('Super Admin')/require_role(['Admin', 'Super Admin'])/g" {} +
find ./admin/students -type f -name "*.php" -exec sed -i 's/require_role("Super Admin")/require_role(["Admin", "Super Admin"])/g' {} +
