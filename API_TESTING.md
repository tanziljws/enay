# API Testing Guide - Galeri Sekolah Enay

Panduan untuk testing API menggunakan curl commands.

## Setup

### Base URL
```bash
BASE_URL="http://localhost:8000/api/v1"
```

### Authentication Token (jika diperlukan)
```bash
TOKEN="your-auth-token-here"
```

## Public Endpoints Testing

### 1. News Endpoints

#### Get All News
```bash
curl -X GET "$BASE_URL/news" \
  -H "Accept: application/json"
```

#### Get Latest News
```bash
curl -X GET "$BASE_URL/news/latest?limit=5" \
  -H "Accept: application/json"
```

#### Get News by ID
```bash
curl -X GET "$BASE_URL/news/1" \
  -H "Accept: application/json"
```

#### Search News
```bash
curl -X GET "$BASE_URL/news?search=matematika&category=academic" \
  -H "Accept: application/json"
```

### 2. Subjects Endpoints

#### Get All Subjects
```bash
curl -X GET "$BASE_URL/subjects" \
  -H "Accept: application/json"
```

#### Get Subject by ID
```bash
curl -X GET "$BASE_URL/subjects/1" \
  -H "Accept: application/json"
```

### 3. Class Rooms Endpoints

#### Get All Class Rooms
```bash
curl -X GET "$BASE_URL/class-rooms" \
  -H "Accept: application/json"
```

#### Get Class Room by ID
```bash
curl -X GET "$BASE_URL/class-rooms/1" \
  -H "Accept: application/json"
```

## Protected Endpoints Testing

### 1. Students Endpoints

#### Get All Students
```bash
curl -X GET "$BASE_URL/students" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Get Students with Filter
```bash
curl -X GET "$BASE_URL/students?class_room_id=1&status=active&search=andi" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Create Student
```bash
curl -X POST "$BASE_URL/students" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "student_number": "S003",
    "name": "Budi Santoso",
    "email": "budi.santoso@student.sekolah-enay.ac.id",
    "phone": "081234567005",
    "date_of_birth": "2007-05-10",
    "gender": "male",
    "address": "Jl. Pondok Indah No. 3, Jakarta",
    "parent_name": "Ahmad Santoso",
    "parent_phone": "081234567105",
    "class_room_id": 1,
    "status": "active"
  }'
```

#### Get Student by ID
```bash
curl -X GET "$BASE_URL/students/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Update Student
```bash
curl -X PUT "$BASE_URL/students/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Andi Pratama Updated",
    "phone": "081234567999"
  }'
```

#### Delete Student
```bash
curl -X DELETE "$BASE_URL/students/1" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 2. Teachers Endpoints

#### Get All Teachers
```bash
curl -X GET "$BASE_URL/teachers" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Create Teacher
```bash
curl -X POST "$BASE_URL/teachers" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "teacher_number": "T003",
    "name": "John Smith, M.A",
    "email": "john.smith@sekolah-enay.ac.id",
    "phone": "081234567892",
    "date_of_birth": "1982-07-10",
    "gender": "male",
    "address": "Jl. Thamrin No. 789, Jakarta",
    "qualification": "S2 English Literature",
    "specialization": "Bahasa Inggris",
    "join_date": "2011-09-01",
    "status": "active",
    "subjects": [3]
  }'
```

### 3. Grades Endpoints

#### Get All Grades
```bash
curl -X GET "$BASE_URL/grades" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Create Grade
```bash
curl -X POST "$BASE_URL/grades" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "subject_id": 1,
    "teacher_id": 1,
    "score": 85.50,
    "grade_letter": "B+",
    "notes": "Nilai bagus",
    "exam_date": "2024-01-15",
    "semester": "1",
    "academic_year": 2024
  }'
```

### 4. Attendance Endpoints

#### Get All Attendance
```bash
curl -X GET "$BASE_URL/attendances" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

#### Create Attendance
```bash
curl -X POST "$BASE_URL/attendances" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "class_room_id": 1,
    "date": "2024-01-15",
    "status": "present",
    "notes": null,
    "check_in_time": "07:00:00",
    "check_out_time": "15:00:00"
  }'
```

## Admin Endpoints Testing

### Dashboard Stats
```bash
curl -X GET "$BASE_URL/admin/dashboard/stats" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

## Testing Script

Buat file `test_api.sh` untuk menjalankan semua test:

```bash
#!/bin/bash

BASE_URL="http://localhost:8000/api/v1"
TOKEN="your-auth-token-here"

echo "Testing Public Endpoints..."

echo "1. Testing News Endpoints"
curl -s -X GET "$BASE_URL/news/latest?limit=3" | jq '.'
echo ""

echo "2. Testing Subjects Endpoints"
curl -s -X GET "$BASE_URL/subjects" | jq '.'
echo ""

echo "3. Testing Class Rooms Endpoints"
curl -s -X GET "$BASE_URL/class-rooms" | jq '.'
echo ""

echo "Testing Protected Endpoints..."

echo "4. Testing Students Endpoints"
curl -s -X GET "$BASE_URL/students" \
  -H "Authorization: Bearer $TOKEN" | jq '.'
echo ""

echo "5. Testing Teachers Endpoints"
curl -s -X GET "$BASE_URL/teachers" \
  -H "Authorization: Bearer $TOKEN" | jq '.'
echo ""

echo "6. Testing Admin Dashboard"
curl -s -X GET "$BASE_URL/admin/dashboard/stats" \
  -H "Authorization: Bearer $TOKEN" | jq '.'
echo ""

echo "All tests completed!"
```

## Expected Responses

### Success Response
```json
{
  "success": true,
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

## Common HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity
- `500` - Internal Server Error

## Tips for Testing

1. **Install jq** for better JSON formatting:
   ```bash
   # Ubuntu/Debian
   sudo apt-get install jq
   
   # macOS
   brew install jq
   
   # Windows
   choco install jq
   ```

2. **Use verbose mode** for debugging:
   ```bash
   curl -v -X GET "$BASE_URL/news"
   ```

3. **Save responses** to files:
   ```bash
   curl -X GET "$BASE_URL/news" -o news_response.json
   ```

4. **Test with different content types**:
   ```bash
   curl -X GET "$BASE_URL/news" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json"
   ```

## Troubleshooting

### Common Issues

1. **CORS Error**: Pastikan server Laravel berjalan dan CORS dikonfigurasi dengan benar
2. **Authentication Error**: Pastikan token valid dan header Authorization dikirim
3. **Validation Error**: Periksa format data yang dikirim sesuai dengan validasi
4. **Database Error**: Pastikan database terhubung dan migrasi sudah dijalankan

### Debug Commands

```bash
# Check if server is running
curl -I http://localhost:8000

# Check API health
curl -X GET http://localhost:8000/api/v1/news/latest

# Check database connection
php artisan migrate:status
```
