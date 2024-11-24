function openPrintPage() {
        try {
            // Owner Information
            const ownerFields = ['ownerName', 'firstName', 'middleName', 'lastName'];
            ownerFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || ''); 
                }
            });
    
            // Property Information
            const propertyFields = [
                'propertyIdModal', 'street', 'barangay', 'municipality', 'province', 
                'houseNumber', 'landArea', 'zoneNumber', 'ardNumber', 
                'taxability', 'effectivity', 'octTctNumber', 'surveyNumber'
            ];
            propertyFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field.replace('Modal', ''), element.value.trim() || '');
                }
            });
    
            // Boundaries
            const boundaryFields = ['north', 'south', 'east', 'west'];
            boundaryFields.forEach(direction => {
                const element = document.getElementById(direction);
                if (element) {
                    localStorage.setItem(`${direction}Boundary`, element.value.trim() || '');
                }
            });
    
            // Boundary Description
            const boundaryDescription = document.getElementById('boundaryDescriptionModal');
            if (boundaryDescription) {
                localStorage.setItem('boundaryDescription', boundaryDescription.value.trim() || '');
            }
    
            // Administrator Information
            const adminFields = [
                'adminLastName', 'adminFirstName', 'adminMiddleName', 
                'adminContact', 'adminEmail', 'adminAddressNumber', 
                'adminAddressStreet', 'adminAddressBarangay', 
                'adminAddressDistrict', 'adminAddressMunicipality', 'adminAddressProvince'
            ];
            adminFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Land Appraisal
            const appraisalFields = [
                'description', 'classification', 'subClass', 'area', 
                'actualUse', 'unitValue', 'marketValue'
            ];
            appraisalFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Value Adjustment Factor
            const adjustmentFields = [
                'adjustmentFactor', 'percentageAdjustment', 'valueAdjustment'
            ];
            adjustmentFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Property Assessment
            const assessmentFields = [
                'adjustedMarketValue', 'assessmentLevel', 'assessedValue'
            ];
            assessmentFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Certification
            const certificationFields = [
                'verifiedBy', 'verifiedDate', 'plottingBy', 'plottingDate', 
                'notedBy', 'notedDate', 'appraisedBy', 'appraisedDate', 
                'recommendingApproval', 'recommendingDate', 'approvedBy', 'approvedDate'
            ];
            certificationFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Miscellaneous
            const miscFields = ['idle', 'contested'];
            miscFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
            // Valuation
            const valuationFields = [
                'landMarketValue', 'landAssessedValue', 
                'plantsMarketValue', 'plantsAssessedValue', 
                'totalMarketValue', 'totalAssessedValue'
            ];
            valuationFields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    localStorage.setItem(field, element.value.trim() || '');
                }
            });
    
  
            window.open('print-layout.html', '_blank'); 
    
        } catch (error) {
            console.error("Error while storing data:", error);
        }
    }
    