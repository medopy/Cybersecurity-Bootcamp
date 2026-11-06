#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main() {
    char filename[100];
    char keyword[50];
    char line[512]; 
    printf("Enter log filename: ");
    scanf("%99s", filename);
    
    printf("Enter keyword to search (e.g., FAILED, 404): ");
    scanf("%49s", keyword);

    FILE *file = fopen(filename, "r");
    if (file == NULL) {
        perror("Error opening log file");
        return 1;
    }

    int line_number = 0;
    int alert_count = 0;

    printf("\n--- Scanning Log File for [%s] ---\n", keyword);

    while (fgets(line, sizeof(line), file) != NULL) {
        line_number++;
        
        if (strstr(line, keyword) != NULL) {
            alert_count++;
            printf("[Alert #%d] Line %d: %s", alert_count, line_number, line);
        }
    }

    printf("\n----------------------------------------\n");
    printf("Scan completed.\n");
    printf("Total alerts found for [%s]: %d\n", keyword, alert_count);

    fclose(file);
    return 0;
}