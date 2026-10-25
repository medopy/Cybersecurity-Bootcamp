#include <stdio.h>
#include <string.h>
#include <ctype.h>

int main() {
    char password[50];
    
    // االمتغيرات الذي عتفحص الشروط 
    int has_length = 0;
    int has_upper = 0;
    int has_lower = 0;
    int has_digit = 0;
    int has_special = 0;

    printf("Enter your password: ");
    
    // قراءة كلمة المرور من المستخدم
    if (fgets(password, sizeof(password), stdin) != NULL) {
       
        password[strcspn(password, "\n")] = '\0';
    }

    int len = strlen(password);

    // يتاكد لو 8 خانات 
    if (len > 8) {
        has_length = 1;
    }

    // يفحص المدخلات 
    for (int i = 0; i < len; i++) {
        if (isupper(password[i])) {
            has_upper = 1;
        } else if (islower(password[i])) {
            has_lower = 1;
        } else if (isdigit(password[i])) {
            has_digit = 1;
        } else if (ispunct(password[i])) {
            has_special = 1;
        }
    }

    // يتاكد من الشروط 
    if (has_length && has_upper && has_lower && has_digit && has_special) {
        printf("Password is valid and strong!\n");
    } else {
        printf("Password is weak. Make sure it has >8 chars, uppercase, lowercase, numbers, and symbols.\n");
    }

    return 0;
}
