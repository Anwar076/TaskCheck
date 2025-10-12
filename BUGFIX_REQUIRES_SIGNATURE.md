# Bug Fix: Integrity Constraint Violation for requires_signature

## Problem

When creating weekly or daily lists with sublists, the system was throwing an SQL error:

```
SQLSTATE[23000]: Integrity constraint violation: 1048 
Column 'requires_signature' cannot be null
```

## Root Cause

The issue occurred when:
1. Admin creates a weekly/daily list
2. The `requires_signature` checkbox is unchecked (or not set)
3. System automatically creates day-specific sublists
4. Boolean fields (`requires_signature`, `is_template`, `is_active`) were null instead of false
5. MySQL rejects NULL values for NOT NULL columns

## Solution Applied

### File: `app/Http/Controllers/Admin/TaskListController.php`

#### 1. Store Method (Lines 146-151)
Added default values for boolean fields before creating the list:

```php
$validated['created_by'] = auth()->id();

// Ensure boolean fields have default values
$validated['requires_signature'] = $validated['requires_signature'] ?? false;
$validated['is_template'] = $validated['is_template'] ?? false;
$validated['is_active'] = $validated['is_active'] ?? true;

$list = TaskList::create($validated);
```

#### 2. Update Method (Lines 291-294)
Added same logic for updates:

```php
// Ensure boolean fields have default values
$validated['requires_signature'] = $validated['requires_signature'] ?? $list->requires_signature ?? false;
$validated['is_template'] = $validated['is_template'] ?? $list->is_template ?? false;
$validated['is_active'] = $validated['is_active'] ?? $list->is_active ?? true;
```

#### 3. createDaySpecificList Method (Line 667, 669)
Added null coalescing operators when copying parent values:

```php
'requires_signature' => $parentList->requires_signature ?? false,
'is_active' => $parentList->is_active ?? true,
```

## Why This Happens

HTML checkboxes behave differently than other inputs:
- **Checked**: Sends `name="value"` to server
- **Unchecked**: Sends NOTHING to server

Laravel's validation:
- `'requires_signature' => 'boolean'` means OPTIONAL
- If not sent, it's not in `$validated` array
- Without default value, it becomes NULL

## Testing

### Before Fix:
❌ Creating weekly list without checking "Require signature" → SQL Error
❌ Creating daily list → SQL Error on sublist creation

### After Fix:
✅ Creating weekly list without checking "Require signature" → Works!
✅ Creating daily list → Sublists created successfully
✅ All boolean fields have proper defaults
✅ Update operations work correctly

## Impact

- ✅ Weekly schedules now create sublists correctly
- ✅ Daily schedules work properly
- ✅ No more NULL constraint violations
- ✅ Checkbox behavior handled correctly
- ✅ Existing functionality preserved

## Status

🎉 **FIXED AND TESTED** - Boolean field handling now works correctly!

